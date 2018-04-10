<?php
use Doctrine\DBAL\Query\QueryBuilder;

class Shopware_Controllers_Backend_MNSearchDebug extends Shopware_Controllers_Backend_ExtJs
{
    /**
     * returns the names of all columns for the given table
     */
    public function getTableColumnsAction()
    {
        $tableName = $this->Request()->getParam('tableName');
        $tables = $this->getTables();
        $tableExists = null;
        $data = [];

        foreach ($tables as $table) {
            $tableExists = array_search($tableName, $table);
            if ($tableExists) {
                break;
            }
        }

        if ($tableExists) {
            //get columns of table
            $sql = 'SHOW COLUMNS FROM ' . $tableName;
            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $this->get('dbal_connection');
            $columns = $connection->fetchAll($sql);

            foreach ($columns as $column) {
                $data[] = [
                    'columnName' => $column['Field'],
                ];
            }

            $this->View()->assign(
                [
                    'success' => true,
                    'data' => $data,
                ]
            );
        } else {
            $this->View()->assign(
                [
                    'success' => false,
                    'message' => 'Passed table does not exist in s_search_tables',
                ]
            );
        }
    }

    /**
     * gets all tables saved in `s_search_tables`
     *
     * @return mixed
     */
    private function getTables()
    {
        /** @var QueryBuilder $builder */
        $builder = $this->get('dbal_connection')->createQueryBuilder();
        $builder->select('sst.id AS tableId', 'sst.table AS tableName')
            ->from('s_search_tables', 'sst');

        return $builder->execute()->fetchAll();
    }
}
