<?php
/**
 * The following variables are exposed:
 *
 * @var array  $block        The block settings and attributes.
 * @var string $content      The block inner HTML (empty).
 * @var bool   $is_preview   True during backend preview render.
 * @var int    $post_id      The post ID the block is rendering content against.
 *                           This is either the post ID currently being displayed inside a query
 *                           loop, or the post ID of the post hosting this block.
 * @var array  $context      The context provided to the block by the post or its parent block.
 */

use USM\DOM_Helper;

$attrs = get_block_wrapper_attributes([
  'class' => 'usm-short-and-socks-tag',
  'style' => DOM_Helper::format_styles_array([
    'align-self'    => get_field('align'),
    'margin-top'    => sprintf("%dpx", get_field('margin_top')),
    'margin-right'  => sprintf("%dpx", get_field('margin_right')),
    'margin-bottom' => sprintf("%dpx", get_field('margin_bottom')),
    'margin-left'   => sprintf("%dpx", get_field('margin_left')),
    'transform'     => sprintf("rotate(%ddeg)", get_field('rotate')),
  ]),
]);

$svgStyles = DOM_Helper::format_styles_array([
  'fill-rule'         => 'evenodd',
  'clip-rule'         => 'evenodd',
  'stroke-linejoin'   => 'round',
  'stroke-miterlimit' => '2',
  'transform'         => sprintf("rotate(%ddeg)", get_field('fixed_icons') ? -get_field('rotate') : 0),
]);
?>

<div <?php echo wp_kses_data($attrs); ?>>
  <svg
    width="100%" height="100%" viewBox="0 0 237 237"
    xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
    style="<?php echo $svgStyles; ?>"
  >
    <path
      d="M236.222,118.111c-0,65.229 -52.88,118.113 -118.113,118.113c-65.229,-0 -118.108,-52.884 -118.108,-118.113c-0,-65.233 52.879,-118.112 118.108,-118.112c65.233,-0 118.113,52.879 118.113,118.112Zm-168.6,-71.396c-3.338,-0 -5.734,0.866 -6.821,4.333l-34.6,112.225c-0.942,3.108 1.087,6.792 4.208,7.662l65.575,18.35c2.975,0.867 6.454,-0.937 7.471,-3.9l14.654,-42.27l14.654,42.27c1.017,2.967 4.496,4.771 7.475,3.905l65.575,-18.355c3.121,-0.866 5.154,-4.554 4.209,-7.658l-34.604,-112.229c-1.088,-3.467 -3.484,-4.333 -6.817,-4.333l-100.979,-0Zm1.158,20.304c3.046,2.383 6.675,4.262 10.879,5.925c10.229,4.121 23.65,6.433 38.45,6.433c14.8,0 28.221,-2.312 38.45,-6.433c4.208,-1.663 7.838,-3.542 10.879,-5.925l28.946,93.871l-53.904,15.029l-18.567,-53.621c-0.796,-2.379 -3.262,-4.188 -5.804,-4.117c-2.541,0 -5.008,1.738 -5.804,4.117l-18.567,53.621l-53.9,-15.029l28.942,-93.871Zm9.938,-8.021l78.779,-0c-1.521,0.942 -3.33,1.733 -5.438,2.6c-8.275,3.325 -20.458,5.567 -33.95,5.567c-13.492,-0 -25.679,-2.242 -33.95,-5.567c-2.104,-0.863 -3.916,-1.658 -5.441,-2.6Z"
    ></path>
  </svg>
  <svg
    width="100%" height="100%" viewBox="0 0 237 237"
    xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
    style="<?php echo $svgStyles; ?>"
  >
    <path
      d="M236.222,118.11c0,65.234 -52.879,118.109 -118.112,118.109c-65.229,-0 -118.108,-52.875 -118.108,-118.109c-0,-65.229 52.879,-118.112 118.108,-118.112c65.233,-0 118.112,52.883 118.112,118.112Zm-121.416,-84.212c-0.729,-0.733 -2.2,-1.471 -3.3,-1.471l-47.338,0c-2.566,0 -4.404,2.204 -4.404,4.404l0,104.213c0,25.321 15.413,36.696 30.458,44.4c8.438,4.404 37.059,18.346 56.146,18.346c22.384,-0 30.084,-13.209 30.084,-25.684c-0,-14.312 -6.605,-23.487 -20.913,-29.725c-17.246,-7.337 -33.391,-17.608 -38.896,-21.279l0,-89.9c-0.366,-1.104 -0.737,-2.204 -1.837,-3.304Zm-46.233,31.925l38.162,-0l0,6.604l-38.162,0l-0,-6.604Zm-0,-23.854l38.162,-0l0,14.679l-38.162,-0l-0,-14.679Zm0.366,39.629l38.163,-0l-0,48.071c-0,1.466 0.733,2.937 1.837,3.671c0.729,0.366 16.142,11.375 35.592,20.545l8.804,4.038c9.542,4.771 13.213,10.275 13.213,20.183c-0,10.642 -7.338,16.513 -20.917,16.513c-2.934,-0 -6.238,-0.367 -9.538,-1.104l-8.812,-2.2c-9.904,-2.934 -20.913,-7.705 -29.721,-12.109l-8.437,-4.404c-12.475,-7.337 -20.184,-16.512 -20.184,-33.392l0,-59.812Z"
    ></path>
  </svg>
</div>
