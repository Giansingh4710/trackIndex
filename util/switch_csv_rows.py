import csv


def getCsvRows(filename):
    csvFile = open(filename, "r")
    csvReader = csv.reader(csvFile)
    rows = [row for row in csvReader]
    csvFile.close()
    return rows


def writeCsvRows(filename, rows):
    csvFile = open(filename, "w")
    csvWriter = csv.writer(csvFile)
    for row in rows:
        csvWriter.writerow(row)


def swapRows(rows):
    # ['created', 'artist', 'description', 'timestamp', 'link', 'shabadID']
    # ['created', 'shabadID', 'type', artist', 'timestamp', 'description', 'link']
    new_rows = []
    for i, row in enumerate(rows):
        data_type = "random"
        if i == 0:
            data_type = "type"

        # new_row = [row[0], row[5], data_type, row[1], row[3], row[2], row[4]]
        new_row = [
            row[0],
            data_type,
            row[1],
            row[3],
            row[5],
            row[2],
            row[4],
        ]
        new_rows.append(new_row)
    return new_rows


rows = getCsvRows("./data.csv")
newRows = swapRows(rows)
if len(rows) != len(newRows):
    print("Error: Length of rows and newRows are not equal")
    exit(1)

writeCsvRows("./test.csv", newRows)
