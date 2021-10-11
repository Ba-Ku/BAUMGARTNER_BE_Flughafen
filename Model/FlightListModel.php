<?php


class FlightListModel
{
    private $resultTableFromQuery;

    public function __construct()
    {
        $this->resultTableFromQuery = array();
    }

    public function queryDatabaseForFlightslist()
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabaseContent();
        $sqlQuery = "SELECT id, flightname FROM `flights` ";
        try {

            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            return $fetchedArray;
        } catch (PDOException $PDOException) {
            error_log("Error: " . $PDOException->getMessage());
        }
    }

}