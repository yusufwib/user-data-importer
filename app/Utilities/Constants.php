<?php

namespace App\Utilities;

class Constants
{
    public const DEFAULT_DB_NAME            = 'user_data_importer';
    public const DEFAULT_BATCH_SIZE         = 100;
    public const CSV_ROWS                   = 3;
    public const ALLOWED_FILE_FORMATS       = 'csv';

    public const PG_DUPLICATE_ERROR_CODE    = '23505';

    public const LOG_TYPE_SUCCESS           = 'SUCCESS';
    public const LOG_TYPE_ERROR             = 'ERROR';
    public const LOG_TYPE_PLAIN             = 'PLAIN';
}
