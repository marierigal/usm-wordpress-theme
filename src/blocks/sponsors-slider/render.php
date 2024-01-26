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

use USM\Taxonomies\Engagement_Level;

$taxonomy = Engagement_Level::TAXONOMY_NAME;
$term_id = $block['data']['engagement_level'] ?? '';
$term = get_term($term_id, $taxonomy);

$query = new WP_Query([
  'post_type'      => 'sponsor',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => [
    'title' => 'ASC',
  ],
  'tax_query'      => [
    [
      'taxonomy' => $taxonomy,
      'field'    => 'term_id',
      'terms'    => $term_id,
    ],
  ],
]);

$is_empty_query = !$query->have_posts();
?>

<?php if (!$is_empty_query || is_admin()): ?>
  <div
    <?php echo wp_kses_data(get_block_wrapper_attributes([
      'class' => 'usm-sponsors-slider' . ($is_empty_query && is_admin() ? ' usm-is-hidden' : ''),
    ])); ?>
  >
    <h2 class="usm-sponsors-slider__title"><?php echo $term->name ?? '...' ?></h2>

    <ol
      class="usm-sponsors-slider__slider"
      data-flickity='{ "wrapAround": <?php echo $query->post_count > 4 ? 'true' : 'false' ?>, "autoPlay": true, "contain": true }'
    >
      <?php while ($query->have_posts()):
        $query->the_post();

        $fields = get_fields(get_the_ID());

        $url = $fields['url'] ?? '';
        $description = $fields['description'] ?? '';
        $logo = $fields['logo'] ?? '';
        $logo_size = 'medium';
        $has_logo = $logo && isset($logo['sizes'][$logo_size]);
        ?>

        <li class="usm-sponsors-slider__slider__slide wp-block-group">
          <?php if ($url): ?>
          <a
            class="usm-sponsors-slider__slider__slide__link" href="<?php echo $url ?>"
            target="_blank"
          >
            <?php endif; ?>

            <?php if ($has_logo): ?>
              <figure
                class="usm-sponsors-slider__slider__slide__thumbnail size-medium"
              >
                <img
                  class="usm-sponsors-slider__slider__slide__thumbnail__image"
                  src="<?php echo $logo['sizes'][$logo_size] ?>"
                  alt=""
                >
              </figure>
            <?php else: ?>
              <div
                class="usm-sponsors-slider__slider__slide__thumbnail usm-sponsors-slider__slider__slide__thumbnail--placeholder size-medium"
              ></div>
            <?php endif; ?>

            <h3 class="usm-sponsors-slider__slider__slide__name"><?php echo the_title() ?></h3>

            <div
              class="usm-sponsors-slider__slider__slide__content"
            ><?php echo wp_strip_all_tags($description) ?></div>

            <?php if ($url): ?>
          </a>
        <?php endif; ?>
        </li>
      <?php
      endwhile;
      ?>
    </ol>
  </div>
<?php endif; ?>

<?php
wp_reset_postdata();
