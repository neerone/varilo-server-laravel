<?php

use Illuminate\Database\Seeder;






class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */






    public function run()
    {


$initialState = '{
  "elementInc": 12,
  "classInc": 12,
  "mediaInc": 12,
  "elements": {
    "0": {
      "id": "0",
      "tag": "div",
      "kind": "body",
      "parentId": "-1",
      "isDragging": false,
      "isOpened": true,
      "isEditing": false,
      "content": [
        "e_1"
      ],
      "classes": [
        "cl_0"
      ]
    },
    "e_1": {
      "id": "e_1",
      "tag": "div",
      "kind": "container",
      "parentId": "0",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": [
        "e_2",
        "e_3",
        "e_4",
        "e_5",
        "e_6",
        "e_7"
      ],
      "classes": [
        "cl_1"
      ]
    },
    "e_2": {
      "id": "e_2",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "e_2",
      "classes": [
        "cl_2"
      ]
    },
    "e_3": {
      "id": "e_3",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "3",
      "classes": [
        "cl_2"
      ]
    },
    "e_4": {
      "id": "e_4",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "4",
      "classes": [
        "cl_2"
      ]
    },
    "e_5": {
      "id": "e_5",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "5",
      "classes": [
        "cl_2"
      ]
    },
    "e_6": {
      "id": "e_6",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "6",
      "classes": [
        "cl_2"
      ]
    },
    "e_7": {
      "id": "e_7",
      "tag": "span",
      "kind": "inline",
      "parentId": "e_1",
      "isDragging": false,
      "isOpened": false,
      "isEditing": false,
      "content": "7",
      "classes": [
        "cl_2"
      ]
    }
  },
  "classes": {
    "cl_0": {
      "pseudos": [],
      "name": "body",
      "media": [],
      "props": [
        {
          "key": "flex-direction",
          "value": "column"
        },
        {
          "key": "overflow",
          "value": "auto"
        }
      ]
    },
    "cl_1": {
      "pseudos": [],
      "name": "hor-container",
      "media": [],
      "props": [
        {
          "key": "width",
          "value": "200px"
        },
        {
          "key": "height",
          "value": "100px"
        },
        {
          "key": "background",
          "value": "#fff8c0"
        },
        {
          "key": "margin",
          "value": "20px"
        }
      ]
    },
    "cl_2": {
      "name": "box-item",
      "pseudos": [
        {
          "name": "hover",
          "media": [
            "11"
          ],
          "props": [
            {
              "key": "padding-top",
              "value": "0px"
            }
          ]
        },
        {
          "name": "first-child",
          "media": [],
          "props": [
            {
              "key": "background",
              "value": "red"
            }
          ]
        }
      ],
      "media": [
        "10"
      ],
      "props": [
        {
          "key": "transition",
          "value": "0.5s all ease"
        },
        {
          "key": "testcolor",
          "value": "#fff #ffffff rgba(1,1,1,0) rgb(1,1,1)"
        },
        {
          "key": "testcolor2",
          "value": "hsl(123, 45%, 67%) hsla(123, 45%, 67%, 0.4)"
        },
        {
          "key": "display",
          "value": "block"
        },
        {
          "key": "float",
          "value": "left"
        },
        {
          "key": "margin",
          "value": "5px"
        },
        {
          "key": "padding",
          "value": "3px"
        },
        {
          "key": "min-width",
          "value": "50px"
        },
        {
          "key": "min-height",
          "value": "50px"
        },
        {
          "key": "background",
          "value": "wheat"
        }
      ]
    }
  },
  "classList": [
    "cl_0",
    "cl_1",
    "cl_2"
  ],
  "mediaList": {
    "320": {
      "screenWidth": 320,
      "name": "Моб. мал.",
      "default": true,
      "mediaLinks": []
    },
    "375": {
      "screenWidth": 375,
      "name": "Моб. ср.",
      "default": true,
      "mediaLinks": []
    },
    "425": {
      "screenWidth": 425,
      "name": "Моб. бол.",
      "default": true,
      "mediaLinks": [
        "10",
        "11"
      ]
    },
    "768": {
      "screenWidth": 768,
      "name": "Планшеты",
      "default": true,
      "mediaLinks": []
    },
    "1024": {
      "screenWidth": 1024,
      "name": "Ноутбуки",
      "default": true,
      "mediaLinks": []
    }
  },
  "media": {
    "10": {
      "id": "10",
      "props": [
        {
          "key": "background",
          "value": "blue"
        }
      ],
      "name": "425"
    },
    "11": {
      "id": "11",
      "props": [
        {
          "key": "background",
          "value": "red"
        }
      ],
      "name": "425"
    }
  }
}';












        DB::table('projects')->insert([
            'name' => "first test project",
            'user_id' => 1,
            'project_state' => $initialState,
        ]);
    }
}
