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

$fields = get_fields(get_the_ID());

$first_name = $fields['prenom'] ?? '';
$last_name = $fields['nom'] ?? '';
$positions = wp_get_post_terms(get_the_ID(), 'position', ['fields' => 'names']);
$height = $fields['taille'] ?? '';
$weight = $fields['poids'] ?? '';
$career = $fields['carriere'] ?? '';
$birth_year = $fields['annee_de_naissance'] ?? '';
$portrait = $fields['photo'] ?? '';
$age = $birth_year ? date('Y') - $birth_year : '';
$portrait_size = 'large';
$has_portrait = $portrait && isset($portrait['sizes'][$portrait_size]);
$portrait_placeholder_url = esc_url(get_stylesheet_directory_uri()) . '/assets/images/player-placeholder.png';
?>

<article class="usm-player wp-block-group">
  <figure class="usm-player__thumbnail wp-block-image size-medium">
    <?php if ($has_portrait): ?>
      <img
        class="usm-player__thumbnail__image"
        src="<?php echo $portrait['sizes'][$portrait_size]; ?>"
        alt=""
      />
    <?php else: ?>
      <img
        class="usm-player__thumbnail__image"
        src="<?php echo $portrait_placeholder_url; ?>"
        alt=""
      />
    <?php endif; ?>
  </figure>

  <div class="usm-player__info wp-block-group has-base-color has-main-background-color">
    <h3 class="usm-player__info__name wp-block-heading">
      <?php echo $first_name . ' ' . strtoupper($last_name); ?>
    </h3>

    <?php if ($positions): ?>
      <p class="usm-player__info__position">
        <?php echo implode(', ', $positions); ?>
      </p>
    <?php endif; ?>

    <?php if ($age || $height || $weight): ?>
      <hr
        class="usm-player__info__separator wp-block-separator has-base-background-color is-style-separator-thin"
      />
    <?php endif; ?>

    <?php if ($age): ?>
      <p class="usm-player__info__age">
        <?php echo $age; ?> <span class="usm-player__info__label">ans</span>
      </p>
    <?php endif; ?>

    <?php if ($height || $weight): ?>
      <p class="usm-player__info__body">
        <?php if ($height): ?>
          <?php echo $height; ?> <span class="usm-player__info__label">cm</span>
        <?php endif; ?>

        <?php if ($height && $weight): ?>
          <span> | </span>
        <?php endif; ?>

        <?php if ($weight): ?>
          <?php echo $weight; ?> <span class="usm-player__info__label">kg</span>
        <?php endif; ?>
      </p>
    <?php endif; ?>

    <?php if ($career): ?>
      <hr
        class="usm-player__info__separator wp-block-separator has-base-background-color is-style-separator-thin"
      />

      <p class="usm-player__info__career">
        <?php echo $career; ?>
      </p>
    <?php endif; ?>
  </div>
</article>
