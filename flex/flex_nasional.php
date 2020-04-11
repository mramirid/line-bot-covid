<?php

$flex = array(
  'type' => 'bubble',
  'hero' =>
  array(
    'type' => 'image',
    'url' => 'https://www.pngkit.com/png/detail/154-1541893_illustration-of-flag-of-indonesia-indonesia-flag-logo.png',
    'size' => 'full',
    'aspectRatio' => '20:13',
    'aspectMode' => 'cover',
    'action' =>
    array(
      'type' => 'uri',
      'uri' => 'http://linecorp.com/',
    ),
  ),
  'body' =>
  array(
    'type' => 'box',
    'layout' => 'vertical',
    'contents' =>
    array(
      0 =>
      array(
        'type' => 'text',
        'text' => 'Indonesia',
        'weight' => 'bold',
        'size' => 'xxl',
      ),
      1 =>
      array(
        'type' => 'box',
        'layout' => 'vertical',
        'margin' => 'lg',
        'spacing' => 'sm',
        'contents' =>
        array(
          0 =>
          array(
            'type' => 'box',
            'layout' => 'baseline',
            'spacing' => 'sm',
            'contents' =>
            array(
              0 =>
              array(
                'type' => 'text',
                'text' => 'Terkonfirmasi',
                'color' => '#aaaaaa',
                'size' => 'sm',
                'flex' => 3,
                'maxLines' => 50,
              ),
              1 =>
              array(
                'type' => 'text',
                'text' => 'replace',
                'wrap' => true,
                'color' => '#666666',
                'size' => 'sm',
                'flex' => 5,
              ),
            ),
          ),
          1 =>
          array(
            'type' => 'box',
            'layout' => 'baseline',
            'spacing' => 'sm',
            'contents' =>
            array(
              0 =>
              array(
                'type' => 'text',
                'text' => 'Dirawat',
                'color' => '#aaaaaa',
                'size' => 'sm',
                'flex' => 3,
                'maxLines' => 50,
              ),
              1 =>
              array(
                'type' => 'text',
                'text' => 'replace',
                'wrap' => true,
                'color' => '#666666',
                'size' => 'sm',
                'flex' => 5,
              ),
            ),
          ),
          2 =>
          array(
            'type' => 'box',
            'layout' => 'baseline',
            'spacing' => 'sm',
            'contents' =>
            array(
              0 =>
              array(
                'type' => 'text',
                'text' => 'Sembuh',
                'color' => '#aaaaaa',
                'size' => 'sm',
                'flex' => 3,
                'maxLines' => 50,
              ),
              1 =>
              array(
                'type' => 'text',
                'text' => 'replace',
                'wrap' => true,
                'color' => '#666666',
                'size' => 'sm',
                'flex' => 5,
              ),
            ),
          ),
          3 =>
          array(
            'type' => 'box',
            'layout' => 'baseline',
            'spacing' => 'sm',
            'contents' =>
            array(
              0 =>
              array(
                'type' => 'text',
                'text' => 'Meninggal',
                'color' => '#aaaaaa',
                'size' => 'sm',
                'flex' => 3,
                'maxLines' => 50,
              ),
              1 =>
              array(
                'type' => 'text',
                'text' => 'replace',
                'wrap' => true,
                'color' => '#666666',
                'size' => 'sm',
                'flex' => 5,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'footer' =>
  array(
    'type' => 'box',
    'layout' => 'vertical',
    'spacing' => 'sm',
    'contents' =>
    array(
      0 =>
      array(
        'type' => 'button',
        'style' => 'link',
        'height' => 'sm',
        'action' =>
        array(
          'type' => 'message',
          'label' => 'KASUS PROVINSI',
          'text' => 'Data Provinsi',
        ),
      ),
      1 =>
      array(
        'type' => 'button',
        'style' => 'link',
        'height' => 'sm',
        'action' =>
        array(
          'type' => 'message',
          'label' => 'CARI',
          'text' => 'Tulis dengan format berikut: Cari:Nama Provinsi',
        ),
      ),
      2 =>
      array(
        'type' => 'button',
        'style' => 'link',
        'height' => 'sm',
        'action' =>
        array(
          'type' => 'message',
          'label' => 'HELP',
          'text' => 'Isi Disini',
        ),
      ),
      3 =>
      array(
        'type' => 'spacer',
        'size' => 'sm',
      ),
    ),
    'flex' => 0,
  ),
);
