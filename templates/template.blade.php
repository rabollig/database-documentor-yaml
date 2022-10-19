<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Database Schema Document</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1">
	    <link
            rel="shortcut icon"
            type="image/png"
            href="data:image/png;base64,
            iVBORw0KGgoAAAANSUhEUgAAAJAAAACQCAIAAABoJHXvAAAEtGlUWHRYTUw6Y29tLmFkb2JlLnht
            cAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQi
            Pz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUg
            NS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIy
            LXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1s
            bnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJo
            dHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDov
            L25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFk
            b2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hh
            cC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9z
            VHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjE0NCIKICAgZXhp
            ZjpQaXhlbFlEaW1lbnNpb249IjE0NCIKICAgZXhpZjpDb2xvclNwYWNlPSIxIgogICB0aWZmOklt
            YWdlV2lkdGg9IjE0NCIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMTQ0IgogICB0aWZmOlJlc29sdXRp
            b25Vbml0PSIyIgogICB0aWZmOlhSZXNvbHV0aW9uPSI3Mi8xIgogICB0aWZmOllSZXNvbHV0aW9u
            PSI3Mi8xIgogICBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIgogICBwaG90b3Nob3A6SUNDUHJvZmls
            ZT0ic1JHQiBJRUM2MTk2Ni0yLjEiCiAgIHhtcDpNb2RpZnlEYXRlPSIyMDIyLTEwLTE4VDE1OjI2
            OjAyLTA0OjAwIgogICB4bXA6TWV0YWRhdGFEYXRlPSIyMDIyLTEwLTE4VDE1OjI2OjAyLTA0OjAw
            Ij4KICAgPHhtcE1NOkhpc3Rvcnk+CiAgICA8cmRmOlNlcT4KICAgICA8cmRmOmxpCiAgICAgIHN0
            RXZ0OmFjdGlvbj0icHJvZHVjZWQiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFmZmluaXR5
            IFBob3RvIDEuMTAuNSIKICAgICAgc3RFdnQ6d2hlbj0iMjAyMi0xMC0xOFQxNToyNjowMi0wNDow
            MCIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgPC9yZGY6RGVzY3JpcHRp
            b24+CiA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgo8P3hwYWNrZXQgZW5kPSJyIj8+hdsLZgAAAYFp
            Q0NQc1JHQiBJRUM2MTk2Ni0yLjEAACiRdZHfK4NRGMc/2wgzES5cuFgaV2ijxI2ypVFLa6YMN9tr
            P9Q2b++7peVWuV1R4savC/4CbpVrpYiUXMo1cYNez2urLdlzes7zOd9znqdzngPWcFrJ6HVuyGRz
            Wsjvdc5HFpwNz9hpx0YTnqiiqxPBYICa9nGHxYw3A2at2uf+tebluK6ApVF4XFG1nPCUcGAtp5q8
            LdyppKLLwqfC/ZpcUPjW1GMlfjE5WeIvk7VwyAfWNmFnsopjVayktIywvBxXJp1XyvcxX+KIZ+dm
            JfaId6MTwo8XJ9NM4mMED2MyjzDAEIOyoka++zd/hlXJVWRWKaCxQpIUOfpFzUv1uMSE6HEZaQpm
            ///2VU8MD5WqO7xQ/2QYb73QsAXfRcP4PDSM7yOwPcJFtpK/egCj76IXK5prH1o34OyyosV24HwT
            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
"
        >
    </head>
    <body>
        <h1>{{ $title }}</h1>
        <div class="index">
            @foreach($schema['tables'] as $tableName => $table)
                <a href="#{{ $tableName }}">{{ $tableName }}</a> <br />
            @endforeach
        </div>

        <h1>Tables</h1>
        @if(empty($schema['tables']))
            <b>No tables. This space intentionally left blank.</b>
        @endif

        @foreach($schema['tables'] as $tableName => $table)
            <h2 id="{{ $tableName }}">{{ $tableName }}</h2>
            Rows: {{ $table['rows'] }} <br />
            Size: {{ $table['bytes'] }} bytes <br />

            @if(!empty($table['triggers']))
                Triggers:
                @foreach($table['triggers'] as $triggerEvent => $statement)
                    <a href="#{{ $tableName }}.trigger.{{ $triggerEvent }}">
                        {{ $triggerEvent }}
                    </a> &nbsp;&nbsp;
                @endforeach
                    <br />
            @endif

            @if(!empty($table['viewCreateQuery']))
                <p class="controlled_width"><code>{{ $table['viewCreateQuery'] }}</code></p>
            @endif

            <div class="controlled_width">
                @if(!empty($table['comment']))
                    <p><i>{{ $table['comment'] }}</i></p>
                @endif

                @if(!empty($table['description']))
                    <p><i>{{ $table['description'] }}</i></p>
                @endif

            </div>

            <table>
            <tr>
                <td class='heading'>Name</td>
                <td class='heading'>Type</td>
                <td class='heading'>Nullable</td>
                <td class='heading'>Key</td>
                <td class='heading'>Default</td>
                <td class='heading'>Samples</td>
                <td class='heading'>Extra</td>
                <td class='heading'>Comment</td>
            </tr>
            @foreach($table['columns'] as $columnName => $columnAttributes)
                    <tr>
                        <td id="{{ $tableName . '.' . $columnName }}">{{ $columnName }}</td>
                        <td>{{ $columnAttributes['type'] ?? '' }}</td>
                        <td>{{ $columnAttributes['nullable'] ?? '' }} </td>
                        <td>{{ $columnAttributes['key'] ?? '' }} </td>
                        <td>{{ (string)$columnAttributes['default'] ?? '' }} </td>
                        <td>
                            @if(!empty($columnAttributes['samples']))
                                <table class="borderless">
                                  @foreach($columnAttributes['samples'] as $sample)
                                    <tr class="borderless">
                                        <td class="borderless">{{ $sample['value'] }}</td>
                                        <td class="borderless align_right">{{ $sample['qty'] }}</td>
                                        <td class="borderless align_right">{{ $sample['percentage'] }}%</td>
                                    </tr>
                                    @endforeach
                                </table>
                            @endif
                        </td>

                        <td>{{ $columnAttributes['extra'] ?? '' }} </td>
                        <td>
                            @if(!empty($columnAttributes['comment']))
                                {{ $columnAttributes['comment'] }}<br /><br />
                            @endif

                            @if(!empty($columnAttributes['description']))
                                {{ $columnAttributes['description'] }}<br /><br />
                            @endif

                            @if(!empty($columnAttributes['foreignKeyConstraint']))
                                Foreign Key Constraint:
                                    <a href="#{{ $columnAttributes['foreignKeyConstraint'] }}">
                                        {{ $columnAttributes['foreignKeyConstraint'] }}
                                    </a>
                                    <br /><br />
                            @endif
                        </td>
                    </tr>
            @endforeach
            </table>

            @if(!empty($table['triggers']))
                <h3>Triggers:</h3>
                @foreach($table['triggers'] as $triggerEvent => $statement)
                    <h4 id="#{{ $tableName }}.trigger.{{ $triggerEvent }}">
                        ON {{ $triggerEvent }}
                    </h4>
                    <pre>{{ $statement }}</pre>
                @endforeach
                <br />
            @endif

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
        a {
            text-decoration: none;
        }
        @media (prefers-color-scheme: dark) {
            body, h1 {
                background-color: #222;
                color: #aaa;
            }
            a {
                color: #ffffff;
            }
        }

        h1, h2 {
            margin-top: 2em;
        }
        table {
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #999;
            padding: 0.5rem;
        }

         .borderless {
             border: none;
         }

        .align_right {
            text-align: right;
        }

        .controlled_width {
            max-width: 60em;
        }
        .heading {
            font-weight: bold;
        }
        .index {
            column-width: 20em;
        }
    </style>

</html>
