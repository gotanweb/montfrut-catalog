<?php
$products = [
  [
    'id' => 'p001',
    'title' => 'Dubai Bar Medium',
    'image' => 'assets/img/dubai-bar.png',
    'description' => 'Chocolate bar with a pistachio cream and kataifi (shredded phyllo dough) filling, encased in dark chocolate.',
    'category' => 'bars',
    'tags' => ['dark', 'pistachios'],
    'price' => 16.00,
    'options' => [
      [
        'name' => 'Size',
        'required' => true,
        'choices' => [
          ['label' => 'Small', 'price' => 0],
          ['label' => 'Medium', 'price' => 0],
          ['label' => 'Large', 'price' => 5.00]
        ]
      ]
    ]
  ],
  [
    'id' => 'p002',
    'title' => 'Bombon Box X6',
    'image' => 'assets/img/bombon-6.jpg',
    'description' => 'Base de chocolate blanco con pistachos y arándanos deshidratados.',
    'category' => 'bombons',
    'tags' => ['white', 'pistachios', 'blueberries'],
    'price' => 23.00,
    'options' => [
      [
        'name' => 'Box Size',
        'required' => true,
        'choices' => [
          ['label' => '6 pieces', 'price' => 0],
          ['label' => '12 pieces', 'price' => 20.00],
          ['label' => '24 pieces', 'price' => 45.00]
        ]
      ],
      [
        'name' => 'Gift Wrapping',
        'required' => false,
        'choices' => [
          ['label' => 'No wrapping', 'price' => 0],
          ['label' => 'Standard box', 'price' => 3.00],
          ['label' => 'Premium gift box', 'price' => 8.00]
        ]
      ]
    ]
  ],
  [
    'id' => 'p003',
    'title' => 'Hexagonal Mendiants X6',
    'image' => 'assets/img/mendiant-hexagonal.jpg',
    'description' => 'Bombón artesanal con chocolate amargo, frutillas deshidratadas y maní.',
    'category' => 'mendiants',
    'tags' => ['dark', 'strawberries', 'peanuts'],
    'price' => 40.00
    // Sin opciones de personalización
  ],
  [
    'id' => 'p004',
    'title' => 'Seasonal Orange Delights',
    'image' => 'assets/img/sample4.jpg',
    'description' => 'Chocolate con naranja deshidratada, edición de temporada.',
    'category' => 'seasonal',
    'tags' => ['dark', 'oranges'],
    'price' => 35.00,
    'options' => [
      [
        'name' => 'Chocolate Type',
        'required' => true,
        'choices' => [
          ['label' => 'Dark Chocolate', 'price' => 0],
          ['label' => 'Milk Chocolate', 'price' => 2.00],
          ['label' => 'White Chocolate', 'price' => 3.00]
        ]
      ]
    ]
  ],
  [
    'id' => 'p005',
    'title' => 'Christmas Mix Box',
    'image' => 'assets/img/sample5.jpg',
    'description' => 'Selección navideña de bombones y frutos secos.',
    'category' => 'christmas',
    'tags' => ['milk', 'walnuts', 'strawberries', 'cashews'],
    'price' => 42.00
    // Sin opciones de personalización
  ]
];
?>