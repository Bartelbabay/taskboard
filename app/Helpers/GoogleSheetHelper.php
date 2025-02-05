<?php

namespace App\Helpers;

class GoogleSheetHelper
{

    var \Google_Service_Sheets $service;
    var string $spreadsheetId;
    var string $range;

    public function __construct()
    {
        $this->initGoogleService();
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '1LiXq3dQfNiAN8ma0Hn6YwdF3I8KnQB2Ln-jhQ2rs6s0');
        $this->range = env('GOOGLE_SPREADSHEET_SHEET_NAME', 'Sheet1') . '!A:E';
    }

    private function initGoogleService() {
        $client = new \Google_Client();
        $client->setApplicationName('Task Board');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->setAccessType('offline');

        $this->service = new \Google_Service_Sheets($client);
    }

    private function prepareGoogleSpreadsheetBody($task) {
        $values = [
            [$task->id, $task->title, $task->description, $task->due_date, $task->is_completed ? 'Completed' : 'Not Completed']
        ];

        return new \Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
    }

    public function updateGoogleSheet($task) {

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $this->range);
        $values = $response->getValues();

        $rowIndex = -1;
        foreach ($values as $index => $row) {
            if (isset($row[0]) && $row[0] == $task->id) {
                $rowIndex = $index;
                break;
            }
        }
        if ($rowIndex !== -1) {
            $range = env('GOOGLE_SPREADSHEET_SHEET_NAME', 'Sheet1') . '!A' . ($rowIndex + 1) . ':E' . ($rowIndex + 1);
            $this->service->spreadsheets_values->update(
                $this->spreadsheetId,
                $range,
                $this->prepareGoogleSpreadsheetBody($task),
                ['valueInputOption' => 'RAW']);
        }
    }

    public function addToGoogleSheet($task)
    {
        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $this->range,
            $this->prepareGoogleSpreadsheetBody($task),
            ['valueInputOption' => 'RAW']);
    }

    public function deleteFromGoogleSheet($taskId) {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $this->range);
        $values = $response->getValues();

        $rowIndex = -1;
        foreach ($values as $index => $row) {
            if (isset($row[0]) && $row[0] == $taskId) {
                $rowIndex = $index;
                break;
            }
        }

        if ($rowIndex !== -1) {
            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => [
                    'deleteDimension' => [
                        'range' => [
                            'sheetId' => 0,
                            'dimension' => 'ROWS',
                            'startIndex' => $rowIndex,
                            'endIndex' => $rowIndex + 1
                        ]
                    ]
                ]
            ]);
            $this->service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        }
    }

}
