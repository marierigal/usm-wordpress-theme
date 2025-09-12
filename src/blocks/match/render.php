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

$default_logo = file_get_contents(__DIR__ . '../../../../assets/images/ffr_full.svg');
$versus_icon = file_get_contents(__DIR__ . '../../../../assets/images/versus.svg');

$sample_match = [
  'match' => [
    'status'     => 'planned',
    'date'        => date('l j F Y'),
    'time'        => date('H:i'),
    'category'    => null,
    'competition' => null,
    'place'        => _x('Place', 'Match info', 'usm'),
    'info'        => _x('Information', 'Match info', 'usm'),
    'other'        => null,
  ],
  'home_team'   => [
    'preset' => false,
    'name'   => _x('Home Team', 'Team name', 'usm'),
    'logo'   => null,
    'score'  => [
      'points' => 0,
      'tries'  => 0,
    ],
  ],
  'away_team'   => [
    'preset' => false,
    'name'   => _x('Away Team', 'Team name', 'usm'),
    'logo'   => null,
    'score'  => [
      'points' => 0,
      'tries'  => 0,
    ],
  ],
];

if (!$match_id) {
  $current_post_post_type = get_post_type($post_id);
  if ($current_post_post_type === 'event') {
    $match_id = $post_id;
  }
}

$status = get_field('match_status', $match_id);

$category_id = get_field('match_category', $match_id);
$category = $category_id ? get_term($category_id, 'category')->name : get_taxonomy('category')->labels->singular_name;

$competition_id = get_field('match_competition', $match_id);
$competition = $competition_id ? get_term($competition_id, 'competition')->name : get_taxonomy('competition')->labels->singular_name;

$place = get_field('match_place', $match_id);

$date = get_field('match_date', $match_id);
$time = get_field('match_time', $match_id);

$info = get_field('match_info', $match_id);

$other = get_field('match_other', $match_id);

$team_preset = get_field('team_preset', 'option');
$team_preset_name = $team_preset['name'] ?: _x('Our Team', 'Team name', 'usm');
$team_preset_logo = $team_preset['logo'] ?: null;

$home_team = get_field('home_team', $match_id);
if ($home_team['preset']) {
  $home_team['name'] = $team_preset_name;
  $home_team['logo'] = $team_preset_logo;
}

$away_team = get_field('away_team', $match_id);
if ($away_team['preset']) {
  $away_team['name'] = $team_preset_name;
  $away_team['logo'] = $team_preset_logo;
}
?>

<div class="usm-match<?php echo $attributes['is_featured'] ? ' is-style-featured' : ''; echo $status === 'canceled' ? ' is-style-canceled' : ''; ?>">
  <p
    class="usm-match__date has-large-font-size"
  ><?php echo $date; echo $time ? ' — ' . $time : ''; ?></p>

  <div class="usm-match__details">
    <p
      class="usm-match__details__category has-base-color has-secondary-background-color"
    ><?php echo $category; ?></p>

    <p
      class="usm-match__details__infos"
    ><?php echo $competition; echo $info ? ' — ' . $info : ''; ?></p>
  </div>

  <?php if ($status === 'over') : ?>
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
          ><?php if ($away_team['score']['tries']) : printf(_nx('%s try', '%s tries', $home_team['score']['tries'], 'Score tries', 'usm'), number_format_i18n($home_team['score']['tries'])); endif; ?></p>
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
          ><?php if ($away_team['score']['tries']) : printf(_nx('%s try', '%s tries', $away_team['score']['tries'], 'Score tries', 'usm'), number_format_i18n($away_team['score']['tries'])); endif; ?></p>
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

      <?php echo $versus_icon; ?>

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

  <?php if ($place) : ?>
  <div class="usm-match__place">
    <div class="usm-match__place__icon wp-block-font-awesome-icon wp-font-awesome-icon">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="location-dot" class="svg-inline--fa fa-location-dot " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
        <path fill="currentColor" d="M0 188.6C0 84.4 86 0 192 0S384 84.4 384 188.6c0 119.3-120.2 262.3-170.4 316.8-11.8 12.8-31.5 12.8-43.3 0-50.2-54.5-170.4-197.5-170.4-316.8zM192 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128z"></path>
      </svg>
    </div>

    <p class="usm-match__place__text"><?php echo $place; ?></p>
  </div>
  <?php endif; ?>

  <?php if ($other) : ?>
    <p class="usm-match__other"><?php echo $other ; ?></p>
  <?php endif; ?>

  <?php if ($status === 'canceled') : ?>
    <div class="usm-match__canceled">
      <p class="usm-match__canceled__label"><?php echo _x('Match canceled', 'Match status', 'usm'); ?></p>
    </div>
  <?php endif; ?>
</div>
