<?php

namespace Drupal\qrcodeforproduct\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\qrcodeforproduct\Service\QrCodeproduct;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'QR Product Generation' Block.
 *
 * @Block(
 *   id = "qrproduct",
 *   admin_label = @Translation("Qr Product Generation"),
 *   category = @Translation("Qr Product Generation"),
 * )
 */
class QrGeneration extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Match the current route.
   */
  protected $routeMatch;

  /**
   * Get the current node id.
   */
  protected $currentNode;

  /**
   * Qr code generater.
   *
   * @var \Drupal\products\Service\QrCodeproduct
   */
  protected $generateQrProduct;

  /**
   * {@inheritdoc}
   */
  public static function create(
      ContainerInterface $container,
      array $configuration,
      $plugin_id,
      $plugin_definition
      ) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('current_route_match'),
          $container->get('qr_code_product')
    );
  }

  /**
   * Creating Block to convert URL to link.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match, QrCodeproduct $generateQrProduct) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $current_route_match;
    $this->currentNode = $this->routeMatch->getParameter('node');
    $this->generateQrProduct = $generateQrProduct;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $product_page_link = $this->currentNode->field_product_url_link->first()->toArray()['uri'];
    $qrChillerlan = $this->generateQrProduct->qrGeneraterChillerlan($product_page_link);
    return [
      '#theme' => 'qr_generation',
      '#qr_code' => $qrChillerlan,
    ];
  }
  
  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
