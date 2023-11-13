import json
import csv
import copy


def js_to_dict(file_name: str) -> dict:
    f = open(file_name, "r", encoding="utf-8")
    lines = f.readlines()
    f.close()
    lines = "".join(lines[1:])
    lines = "{" + lines
    return json.loads(lines)


def same_sbds(sbd1: list, sbd2: list) -> bool:
    for i in range(0, len(sbd1), 3):
        if i == len(sbd2):
            break
        old_dict_line = sbd1[i]
        new_dict_line = sbd2[i].replace(".", "").replace(",", "")
        if old_dict_line != new_dict_line:
            # print(old_dict_line)
            # print(new_dict_line)
            return False
    return True


sbd1_dicts = {}
sbd2_dicts = {}


def get_sbd_dict(type_, id) -> dict[str, int]:
    sbd = []
    if type_ == "old":
        if id in sbd1_dicts:
            return copy.deepcopy(sbd1_dicts[id])
        sbd = old_dict[id]
    else:
        if id in sbd2_dicts:
            return sbd2_dicts[id]
        sbd = new_dict[id]

    sbd_dict = {"total_words": 0}
    for i in range(0, len(sbd), 3):
        for word in sbd[i].replace(".", "").replace(",", "").split(" "):
            sbd_dict["total_words"] += 1
            if word in sbd_dict:
                sbd_dict[word] += 1
            else:
                sbd_dict[word] = 1

    if type_ == "old":
        sbd1_dicts[id] = copy.deepcopy(sbd_dict)
    else:
        sbd2_dicts[id] = copy.deepcopy(sbd_dict)
    return sbd_dict


def match(old_id: str, new_id: str) -> float:
    sbd1_words = get_sbd_dict("old", old_id)
    sbd2_words = get_sbd_dict("new", new_id)
    total_words = sbd1_words["total_words"] + sbd2_words["total_words"]
    same_words = 0

    for word in sbd1_words:
        if word in sbd2_words:
            same_words += min(sbd1_words[word], sbd2_words[word])

    return same_words / total_words


def old_id_to_new_id(old_id: str):
    shabad_to_match = old_dict[old_id]
    higest_match = 0.0
    new_id = None
    for key in new_dict:
        if key == "5550":
            break

        if same_sbds(shabad_to_match, new_dict[key]):
            return key

        match_percent = match(old_id, key)
        if match_percent > higest_match:
            higest_match = match_percent
            new_id = key
            # print(old_id, key, match_percent, higest_match)

    # print(old_id, new_id, higest_match)
    return new_id


def write_to_csv(file_path, data):
    with open(file_path, "w") as file:
        csv_writer = csv.writer(file)
        for row in data:
            csv_writer.writerow(row)


old_dict = js_to_dict("./allShabads_old.js")
new_dict = js_to_dict("./allShabads.js")

def updated_ids_csv():
    file_path = "./data.csv"
    updated_data = []

    with open(file_path, "r") as file:
        csv_reader = csv.reader(file)
        for row in csv_reader:
            if row[-1] == "ShabadId" or row[-1] == "":
                print(row)
                updated_data.append(row)
                continue
            if row[-1] in old_dict:
                new_id = old_id_to_new_id(row[-1])
                row[-1] = new_id
            updated_data.append(row)
    return updated_data

# write_to_csv("new_data1.csv", updated_ids_csv())
