# database-documentor-yaml

I'm working on an OpenAPI-like tool to automate making database 
schema documentation.


## Quickstart

- Copy `config.ini.example` to `config.ini` and update with a read-only database user
- Scan your database to create a YAML file using `php database_scanner.php > schema.yaml`
- Compile the YAML file to HTML documentation with `php html_doc_builder.php > doc.html`

## Future direction

My ultimate goal is to be able to move back and forth between SQL, a database, and YAML... and 
still autogenerate the documentation. 

The idea is that you'll run the database scanner to initially produce documentation. You 
can hand edit the YAML to include comments, etc. If you are really good, you could produce
the YAML first... then the DDL generator will make the SQL commands you need to create the 
database, but this will probably be rare. There are also too many migration systems for me
to support a large number of them.

When your documentation gets out of phase with your database because of lazy changes, you can
re-scan and diff your YAML files to update the documentation.

