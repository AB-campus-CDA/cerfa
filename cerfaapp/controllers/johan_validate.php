<?php

function isValidDate($date, $format = 'Y-m-d') {
    $dateTime = DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
}
function validate($model, $data)
{
    $FILLED = [];
    foreach($model as $field => $rules)
    {
        $value = @$data[$field];
        $mandatoryNotSatisfied = isset($rules['mandatory']) && $rules['mandatory'] === true && !$value;
        $dependencyNotSatisfied = isset($rules['dependency']) && (in_array($data[$rules['dependency']['field']], array_keys($rules['dependency']['values'])) && !$value);
        if ($mandatoryNotSatisfied || $dependencyNotSatisfied) return "missing field '$field'";

        if (isset($value)) {
            if ($rules['type'] === 'date' && !isValidDate($value)) {
                return "incompatible date format for field '$field'";
            }
            if ($rules['type'] !== 'date' && gettype($value) !== $rules['type']) {
                return "incompatible type for field '$field'";
            }
            if (isset($rules['dependency'])) {
                $dependency = $rules['dependency']['field'];
                if (isset($rules['dependency']['values'][$data[$dependency]]))
                    foreach ($rules['dependency']['values'][$data[$dependency]] as $subfield => $subvalue) {
                        if ($rules['type'] === 'date') {
                            $FILLED[$subfield] = DateTime::createFromFormat('Y-m-d', $value)->format($subvalue);
                        } else {
                            $FILLED[$subfield] = $subvalue;
                        }
                    }
            } else {
                $FILLED[$rules['field']] = $value;
            }
        }
    }
    return $FILLED;
}

$model = json_decode('{
	"asso_name":{
		"type": "string",
		"mandatory": true,
		"field": "a3"
	},
	"asso_street":{
		"type": "string",
		"mandatory": false,
		"field": "a6"
	},
	"asso_siren":{
		"type": "string",
		"mandatory": true,
		"field": "a4"
	},
	"asso_type": {
		"type": "string",
		"mandatory": true,
		"dependency": {
			"field": "asso_type",
			"values": {
				"LOI1901": {
					"CAC0": 1,
					"CAC1": 1
				},
				"FRUP": {
					"CAC0": 2,
					"CAC1": 1
				},
				"FRUP_MOZEL": {
					"CAC0": 2,
					"CAC1": 1
				},
				"ASS_CULT": {
					"CAC2": 1
				}
			}
		}
	},
	"date": {
		"type": "date",
		"dependency": {
			"field": "asso_type",
			"values": {
				"FRUP": {
					"d12": "d/m/Y"
				},
				"FRUP_MOZEL": {
					"d14": "d/m/Y"
				},
				"SCIENTIFIC": {
					"a14": "d/m/Y"
				},
				"HISTORIC": {
					"a15": "d/m/Y"
				}
			}
		}
	},
	"date2": {
		"type": "date",
		"dependency": {
			"field": "asso_type",
			"values": {
				"FRUP": {
					"d13": "d/m/Y"
				}
			}
		}
	}
}', true);
$data = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "LOI1901"
}', true);
$data2 = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "FRUP",
	"date": "2023-01-01",
	"date2": "2023-01-01"
}', true);
$data3 = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "FRUP_MOZEL",
	"date": "2023-01-01"
}', true);
echo json_encode( validate($model, $data) ) ." >> Done". PHP_EOL;
echo json_encode( validate($model, $data2) ) ." >> Done". PHP_EOL;
echo json_encode( validate($model, $data3) ) ." >> Done". PHP_EOL;



$data = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "FRUP"
}', true);
$data2 = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "FRUP",
	"date": "2023-01-01"
}', true);
$data4 = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": "FRUP",
	"date": "01/01/2023",
	"date2": "2023-01-01"
}', true);
$data3 = json_decode('{
    "donor_type": "INDIVIDUAL",
	"asso_siren": "SPA",
	"asso_name": "LA SPA",
	"asso_street": "Paris",
	"asso_type": ""
}', true);
echo json_encode( validate($model, $data) ) ." >> Done". PHP_EOL;
echo json_encode( validate($model, $data2) ) ." >> Done". PHP_EOL;
echo json_encode( validate($model, $data4) ) ." >> Done". PHP_EOL;
echo json_encode( validate($model, $data3) ) ." >> Done". PHP_EOL;