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

$attributes = $block['data'];

$match_id = $attributes['match'] ?: null;
if (!$is_preview && !$match_id) return '';

$default_logo = file_get_contents(USM_ASSETS . '/images/ffr_full.svg');
$versus_icon = file_get_contents(USM_ASSETS . '/images/versus.svg');

$sample_match = [
  'event_type'  => 'match',
  'is_over'     => true,
  'date'        => date('l j F Y'),
  'category'    => null,
  'competition' => null,
  'info'        => _x('Information', 'Match info', 'usm'),
  'home_team'   => [
    'preset' => false,
    'name'   => _x('Home Team', 'Team name', 'usm'),
    'logo'   => null,
    'score'  => [
      'points' => 27,
      'tries'  => 3,
    ],
  ],
  'away_team'   => [
    'preset' => false,
    'name'   => _x('Away Team', 'Team name', 'usm'),
    'logo'   => null,
    'score'  => [
      'points' => 16,
      'tries'  => 1,
    ],
  ],
];

if (!$match_id) {
  $current_post_post_type = get_post_type($post_id);
  if ($current_post_post_type === 'event') {
    $match_id = $post_id;
  }
}

$match = $match_id ? get_fields($match_id) : $sample_match;
if ($match['event_type'] !== 'match') return $is_preview ? __('Match not found', 'usm') : '';

$team_preset = get_field('team_preset', 'option');
$team_preset_name = $team_preset['name'] ?: _x('Our Team', 'Team name', 'usm');
$team_preset_logo = $team_preset['logo'] ?: $default_logo;

$is_over = $match['is_over'];
$category_id = $match['category'];
$category = $category_id ? get_term($category_id, 'category')->name : get_taxonomy('category')->labels->singular_name;
$competition_id = $match['competition'];
$competition = $competition_id ? get_term($competition_id, 'competition')->name : get_taxonomy('competition')->labels->singular_name;
$date = $match['date'];
$info = $match['info'];

$home_team = $match['home_team'];
if ($home_team['preset']) {
  $home_team['name'] = $team_preset_name;
  $home_team['logo'] = $team_preset_logo;
}

$away_team = $match['away_team'];
if ($away_team['preset']) {
  $away_team['name'] = $team_preset_name;
  $away_team['logo'] = $team_preset_logo;
}
?>

<div class="usm-match<?php echo $attributes['is_featured'] ? ' is-style-featured' : ''; ?>">
  <p
    class="usm-match__date has-large-font-size"
  ><?php echo $date ?></p>

  <div class="usm-match__details">
    <p
      class="usm-match__details__category has-main-color has-tertiary-background-color"
    ><?php echo $category; ?></p>

    <p
      class="usm-match__details__infos"
    ><?php echo $competition; ?><?php echo $info ? ' — ' . $info : ''; ?></p>
  </div>

  <?php if ($attributes['is_displaying_score']) : ?>
    <div class="usm-match__teams">
      <hr class="usm-match__teams__decorator" />

      <p
        class="usm-match__teams__name has-heading-font-family has-xx-large-font-size"
      ><?php echo $home_team['name']; ?></p>

      <?php echo $versus_icon; ?>

      <p
        class="usm-match__teams__name has-heading-font-family has-xx-large-font-size"
      ><?php echo $away_team['name']; ?></p>

      <hr class="usm-match__teams__decorator" />
    </div>

    <div class="usm-match__scores">
      <div class="usm-match__scores__team usm-match__scores__team--home">
        <?php if ($home_team['logo']) : ?>
          <figure class="usm-match__scores__team__logo wp-block-image">
            <img
              src="<?php echo $home_team['logo']; ?>"
              alt="<?php echo $home_team['name']; ?>"
            />
          </figure>
        <?php else : echo $default_logo; endif; ?>

        <div class="usm-match__scores__team__score">
          <p
            class="usm-match__scores__team__score__points"
          ><?php echo $home_team['score']['points']; ?></p>

          <p
            class="usm-match__scores__team__score__tries has-small-font-size"
          ><?php printf(_nx('%s try', '%s tries', $home_team['score']['tries'], 'Score tries', 'usm'), number_format_i18n($home_team['score']['tries'])); ?></p>
        </div>
      </div>

      <p class="usm-match__scores__separator" aria-label="versus">—</p>

      <div class="usm-match__scores__team usm-match__scores__team--away">
        <?php if ($away_team['logo']) : ?>
          <figure class="usm-match__scores__team__logo wp-block-image">
            <img
              src="<?php echo $away_team['logo']; ?>"
              alt="<?php echo $away_team['name']; ?>"
            />
          </figure>
        <?php else : echo $default_logo; endif; ?>

        <div class="usm-match__scores__team__score">
          <p
            class="usm-match__scores__team__score__points"
          ><?php echo $away_team['score']['points']; ?></p>

          <p
            class="usm-match__scores__team__score__tries has-small-font-size"
          ><?php printf(_nx('%s try', '%s tries', $away_team['score']['tries'], 'Score tries', 'usm'), number_format_i18n($away_team['score']['tries'])); ?></p>
        </div>
      </div>
    </div>
  <?php else : ?>
    <div class="usm-match__teams usm-match__teams--without-score">
      <div class="usm-match__teams__wrapper">
        <?php if ($home_team['logo']) : ?>
          <figure class="usm-match__teams__wrapper__logo wp-block-image">
            <img
              src="<?php echo $home_team['logo']; ?>"
              alt="<?php echo $home_team['name']; ?>"
            />
          </figure>
        <?php else : echo $default_logo; endif; ?>
        <p
          class="usm-match__teams__wrapper__name has-heading-font-family has-xx-large-font-size"
        ><?php echo $home_team['name']; ?></p>
      </div>

      <?php echo file_get_contents(USM_ASSETS . '/images/versus.svg'); ?>

      <div class="usm-match__teams__wrapper">
        <?php if ($away_team['logo']) : ?>
          <figure class="usm-match__scores__team__logo wp-block-image">
            <img
              src="<?php echo $away_team['logo']; ?>"
              alt="<?php echo $away_team['name']; ?>"
            />
          </figure>
        <?php else : echo $default_logo; endif; ?>
        <p
          class="usm-match__teams__wrapper__name has-heading-font-family has-xx-large-font-size"
        ><?php echo $away_team['name']; ?></p>
      </div>
    </div>
  <?php endif; ?>
</div>
