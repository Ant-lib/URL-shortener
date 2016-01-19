<?php

class shortener_processor {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
        $query = "SELECT sshorturl FROM ushr.tshorturls WHERE slongurl = :slongurl LIMIT 1;";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "slongurl" => $url
        );
        $stmt->execute($params);
        $result = $stmt->fetch();

        return (empty($result)) ? false : $result["sshorturl"];
    }

    protected function createShortCode($url) {
        $id = $this->insertUrlInDb($url);
        $shortCode = substr(md5(time().$url), 0, 5);
        $this->insertShortCodeInDb($id, $shortCode);

        return $shortCode;
    }

    protected function insertUrlInDb($url) {
        $query = "INSERT INTO ushr.tshorturls (slongurl) VALUES (:slongurl) RETURNING iid;";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "slongurl" => $url
        );
        $stmnt->execute($params);
        $result = $stmnt->fetch(PDO::FETCH_ASSOC); 

        return $result["iid"];
    }

    protected function insertShortCodeInDb($id, $code) {
        if ($id == null || $code == null) {
            throw new \Exception("Incorrect variables passed.");
        }
        $query = "UPDATE ushr.tshorturls SET sshorturl = :sshorturl WHERE iid = :iid";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "sshorturl" => $code,
            "iid" => $id
        );
        $stmnt->execute($params);

        if ($stmnt->rowCount() < 1) {
            throw new \Exception("Update failed.");
        }

        return true;
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
        $query = "SELECT slongurl FROM ushr.tshorturls WHERE sshorturl = :sshorturl LIMIT 1;";
        $stmt = $this->pdo->prepare($query);
        $params=array(
            "sshorturl" => $code
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result["slongurl"])) ? false : $result["slongurl"];
    }
}

?>