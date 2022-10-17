<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Database Schema Document</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="shortcut icon" href="/favicon.svg">
    </head>
    <body>
        <h1>Database Documentation</h1>
        <div class="index">
            @foreach($schema['tables'] as $tableName =>$table)
                <a href="#{{ $tableName }}">{{ $tableName }}</a> <br />
            @endforeach
        </div>

        <h1>Tables</h1>
        @if(empty($schema['tables']))
            <b>No tables. This space intentionally left blank.</b>
        @endif

        @foreach($schema['tables'] as $tableName =>$table)
            <h2 id="{{ $tableName }}">{{ $tableName }}</h2>
            <table>
            <tr>
                <td class='heading'>Name</td>
                <td class='heading'>Type</td>
                <td class='heading'>Nullable</td>
                <td class='heading'>Key</td>
                <td class='heading'>Default</td>
                <td class='heading'>Extra</td>
                <td class='heading'>Comment</td>
            </tr>
            @foreach($table['columns'] as $columnName => $columnAttributes)
                    <tr>
                        <td>{{ $columnName }}</td>
                        <td>{{ $columnAttributes['type'] ?? '' }}</td>
                        <td>{{ $columnAttributes['nullable'] ?? '' }} </td>
                        <td>{{ $columnAttributes['key'] ?? '' }} </td>
                        <td>{{ (string)$columnAttributes['default'] ?? '' }} </td>
                        <td>{{ $columnAttributes['extra'] ?? '' }} </td>
                        <td>{{ $columnAttributes['comment'] ?? '' }}</td>
                    </tr>
            @endforeach
            </table>
        @endforeach

        <h1>Views</h1>
        @if(empty($schema['views']))
            <b>No views. This space intentionally left blank.</b>
        @endif

    </body>

    <style>
        body {
            font-family: Roboto, sans-serif;
            margin: 2em;
        }

        h1 {
            color: #333333;
        }

        table {
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #999;
            padding: 0.5rem;
        }

        .heading {
            font-weight: bold;
        }
        .index {
            column-width: 20em;
        }
    </style>

</html>
