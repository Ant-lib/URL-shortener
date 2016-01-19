<?php

class shortener_processor {

    protected $dbconn;
    public function __construct($dbconn) {
        $this->dbconn = $dbconn;
    }

    public function saveShortCode($url) {
        if (empty($url)) {throw new \Exception("No url to process.");
        }

        $shortCode = $this->urlExistsInDb($url);
        if ($shortCode == false) {
            $shortCode = $this->createShortCode($url);
        }

        return $shortCode;
    }

    protected function urlExistsInDb($url) {
        $this->dbconn->query("SELECT sshorturl FROM ushr.tshorturls WHERE slongurl = :slongurl AND now() <= dtexpired;");
        $this->dbconn->bind(':slongurl', $url);
        $result = $this->dbconn->single();

        return (empty($result["sshorturl"])) ? false : $result["sshorturl"];
    }

    protected function createShortCode($url) {
        $shortCode = substr(md5(time().$url), 0, 5);
        $this->dbconn->query("INSERT INTO ushr.tshorturls (slongurl, sshorturl) VALUES (:slongurl, :sshorturl) RETURNING iid;");
        $this->dbconn->bind(':slongurl', $url);
        $this->dbconn->bind(':sshorturl', $shortCode);
        $this->dbconn->execute();

        return $shortCode;
    }

    public function getShortCode($code) {
        if (empty($code)) {
            throw new \Exception("No short code passed.");
        }

        $urlRow = $this->getUrlFromDb($code);

        if (empty($urlRow)) {
            throw new \Exception("No short code was found.");
        }

        return $urlRow;
    }

    protected function getUrlFromDb($code) {
        $this->dbconn->query("SELECT slongurl FROM ushr.tshorturls WHERE sshorturl = :sshorturl;");
        $this->dbconn->bind(':sshorturl', $code);
        $result = $this->dbconn->single();

        return (empty($result["slongurl"])) ? false : $result["slongurl"];
    }
}

?>