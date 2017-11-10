
<?php

/**
 * This class implement the application Logic for rendering services 
 * 
 * */

require 'database.php';

class ApplicationFunctions
{

    public function __construct(){ }

    public function __destruct() { }

    public function IdentifyUser($msisdn)
    {
        $db = Database::getInstance();

        try {

            $stmt = $db->prepare("insert into sessionmanager(msisdn) values (:msisdn)");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            }

        } catch (PDOException $e) {
            #$e->getMessage();
            return false;
        }
    }

    /**
     * Delete the session a session entry in the database.
     * 
     * */
    public function deleteSession($msisdn)
    {

        $db = Database::getInstance();

        try
        {

            $stmt = $db->prepare("Delete FROM sessionmanager where msisdn= :msisdn");

            $stmt->bindParam(":msisdn", $msisdn);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            }

        } catch (PDOException $e) {

            #echo $e->getMessage();

            return false;

        }

    }

    /**
     *Method to reset a users session to the first case. (Delete all of the users records except his msisdn)
     *@param msisdn
     *@return Boolean
     */

    public function deleteAllSession($msisdn)
    {

        $db = Database::getInstance();

        try

        {

            $stmt = $db->prepare("UPDATE sessionmanager SET transaction_type = NULL where msisdn= :msisdn");

            $stmt->bindParam(":msisdn", $msisdn);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                return true;

            }

        } catch (PDOException $e) {

            #echo $e->getMessage();

            return false;

        }

    }

    /**
     *Method to update user session with the actual type of transaction or details of the transaction *currently being held
     *@param msisdn, collumn, transaction type
     *@param Boolean
     **/

    public function UpdateTransactionType($msisdn, $col, $trans_type)
    {

        $db = Database::getInstance();

        try

        {

            $stmt = $db->prepare("update sessionmanager set " . $col . " = :trans_type where msisdn = :msisdn");

            $params = array(":msisdn" => $msisdn, ":trans_type" => $trans_type);

            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {

                return true;

            }

        } catch (PDOException $e) {

            #echo $e->getMessage();

            return false;

        }

    }

    /**
     *Method to query specific details from the session manager. (Get value held in a specific column)
     *@param msisdn, specific column to query
     *@return string
     */

    public function GetTransactionType($msisdn, $col)
    {

        $db = Database::getInstance();

        try

        {

            $stmt = $db->prepare("SELECT " . $col . " FROM  sessionmanager WHERE  msisdn = :msisdn");

            $stmt->bindParam(":msisdn", $msisdn);

            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res !== false) {

                return $res[$col];

            }

        } catch (PDOException $e) {

            #echo $e->getMessage();

            return null;

        }

    }

    /**

     *Method to query users session state. checking if the user has an existing session and if so the session count.
     *@param msisdn, specific column to query
     *@return string
     */

    public function sessionManager($msisdn)
    {

        $db = Database::getInstance();

        try

        {

            $stmt = $db->prepare("SELECT (COUNT(msisdn)+ COUNT(transaction_type)) AS counter FROM sessionmanager WHERE msisdn = :msisdn");

            $stmt->bindParam(":msisdn", $msisdn);

            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res !== false) {

                return $res['counter'];

            }

        } catch (PDOException $e) {

            #echo $e->getMessage();

            return null;

        }

    }

}
