<?php

return [
    "documents" => [
        "title" => "Εγγραφα",
        "group" => "Content",
        "single" => "Εγγραφο",
        "form" => [
            "ref" => "Reference",
            "id" => "ID",
            "model" => "Αφορά",
            "document_template_id" => "Template",
            "document" =>  "Εγγραφο",
            "body" =>  "Σωμα",
            "is_send" =>  "Αποστολη",
            "template" => "Μητρα",
            "values" => "Τιμες",
            "var-value" => "Τιμη",
            "var-label" => "Ταμπελα",
        ],
        "actions" => [
            "print" => "Εκτυπωση",
            "document" => [
                "title" => "Δημιουργια Εκτυπωτικού",
                "notification" => [
                    "title" => "Το Εγγραφο Δημιουργήθηκε",
                    "body" => "Το έγγραφο εχει δημιουργηθεί.",
                    "action" => "Επισκόπηση Εγγράφου",
                ]
            ]
        ]
    ],
    "document-templates" => [
        "title" => "Document Templates",
        "group" => "Content",
        "single" => "Template",
        "form" => [
            "name" => "Name",
            "vars" => "Variables",
            "vars-key" => "Key",
            "vars-label" => "Value",
            "is_active" => "Is Active",
            "body" => "Body",
            "icon" => "Icon",
            "color" => "Color",
        ]
    ],
    "vars" => [
        "day" => "Day",
        "date" => "Date",
        "time" => "Time",
        "random" => "Random",
        "uuid" => "UUID",
    ],
];
