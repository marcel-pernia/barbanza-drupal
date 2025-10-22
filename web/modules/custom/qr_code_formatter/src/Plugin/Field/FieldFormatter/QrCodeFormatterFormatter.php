<?php

namespace Drupal\qr_code_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\link\LinkItemInterface;
use chillerlan\QRCode\QRCode;

/**
 * Plugin implementation of the 'QR Code formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "qr_code_formatter_qr_code_formatter",
 *   label = @Translation("QR Code formatter"),
 *   field_types = {"link"},
 * )
 */
class QrCodeFormatterFormatter extends FormatterBase {

  /**
   * Builds a renderable array for a field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface<LinkItemInterface> $items
   *   The field values to be rendered.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A renderable array for $items, as an array of child elements keyed by
   *   consecutive numeric indexes starting from 0.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {

    $element = [];
    foreach ($items as $delta => $item) {
      if ($item instanceof LinkItemInterface) {
        $url = $item->getUrl();
        $url->setAbsolute(TRUE);
        $element[$delta] = [
          'title' => [
            '#type' => 'html_tag',
            '#tag' => 'h2',
            '#value' => $item->getTitle(),
          ],
          'image' => [
            '#theme' => 'image',
            '#uri' => (new QRCode)->render($url->toString()),
            '#alt' => $url->toString(),
          ],
        ];
      }
    }
    return $element;
  }

}
