<?php
/**
 * Logged-in full dictionary view for DSF page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Restrict letter filter to A-Z.
 *
 * @param string $letter Raw input.
 * @return string
 */
function dsf_dictionary_normalize_letter( $letter ) {
	$letter = strtoupper( substr( sanitize_text_field( wp_unslash( (string) $letter ) ), 0, 1 ) );
	return preg_match( '/^[A-Z]$/', $letter ) ? $letter : '';
}

/**
 * Add title initial filter to DSF logged dictionary query.
 *
 * @param string   $where SQL WHERE.
 * @param WP_Query $query Query object.
 * @return string
 */
function dsf_dictionary_posts_where( $where, $query ) {
	global $wpdb;

	if ( ! $query->get( 'dsf_logged_dictionary' ) ) {
		return $where;
	}

	$letter = $query->get( 'dsf_logged_dictionary_letter' );
	if ( ! $letter ) {
		return $where;
	}

	$like   = $wpdb->esc_like( $letter ) . '%';
	$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", $like );
	return $where;
}
add_filter( 'posts_where', 'dsf_dictionary_posts_where', 10, 2 );

/**
 * Build the logged-in full dictionary content.
 *
 * @return string
 */
function dsf_dictionary_render_logged_view() {
	$search_term = '';
	if ( isset( $_GET['dsf_q'] ) ) {
		$search_term = sanitize_text_field( wp_unslash( (string) $_GET['dsf_q'] ) );
	}

	$letter = '';
	if ( isset( $_GET['dsf_letter'] ) ) {
		$letter = dsf_dictionary_normalize_letter( $_GET['dsf_letter'] );
	}

	$page = 1;
	if ( isset( $_GET['dsf_page'] ) ) {
		$page = max( 1, absint( $_GET['dsf_page'] ) );
	}

	$query_args = array(
		'post_type'                    => 'post',
		'post_status'                  => 'publish',
		'orderby'                      => 'title',
		'order'                        => 'ASC',
		'posts_per_page'               => 40,
		'paged'                        => $page,
		'dsf_logged_dictionary'        => true,
		'dsf_logged_dictionary_letter' => $letter,
	);

	if ( '' !== $search_term ) {
		$query_args['s'] = $search_term;
	}

	$query = new WP_Query( $query_args );

	$current_url = get_permalink();
	if ( ! $current_url ) {
		$current_url = home_url( '/dicionario-dsf/' );
	}

	$alphabet_links = array();
	foreach ( range( 'A', 'Z' ) as $char ) {
		$url = add_query_arg(
			array(
				'dsf_letter' => $char,
				'dsf_q'      => $search_term,
			),
			$current_url
		);

		$class            = ( $letter === $char ) ? ' class="is-active"' : '';
		$alphabet_links[] = '<a' . $class . ' href="' . esc_url( $url ) . '">' . esc_html( $char ) . '</a>';
	}

	$clear_url = remove_query_arg( array( 'dsf_q', 'dsf_letter', 'dsf_page' ), $current_url );

	ob_start();
	?>
	<section class="dsf-full-dictionary">
		<style>
			.dsf-full-dictionary { max-width: 1160px; margin: 0 auto; padding: 170px 20px 40px; }
			.dsf-full-dictionary h2 { margin: 0 0 10px; font-size: 42px; line-height: 1.15; color: #112f66; }
			.dsf-full-dictionary p { margin: 0 0 18px; color: #3d4d68; font-size: 18px; }
			.dsf-full-dictionary .dsf-search { display: flex; gap: 10px; flex-wrap: wrap; margin: 14px 0 18px; }
			.dsf-full-dictionary .dsf-search input[type="search"] { flex: 1 1 420px; min-height: 50px; border: 1px solid #bac5d9; border-radius: 10px; padding: 10px 14px; font-size: 16px; }
			.dsf-full-dictionary .dsf-search button,
			.dsf-full-dictionary .dsf-search a { min-height: 50px; border-radius: 10px; padding: 12px 18px; border: 1px solid #1d4b9f; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; }
			.dsf-full-dictionary .dsf-search button { background: #1d4b9f; color: #fff; cursor: pointer; }
			.dsf-full-dictionary .dsf-search a { background: #fff; color: #1d4b9f; }
			.dsf-full-dictionary .dsf-alphabet { display: flex; flex-wrap: wrap; gap: 6px; margin: 0 0 20px; }
			.dsf-full-dictionary .dsf-alphabet a { width: 34px; height: 34px; border: 1px solid #c2cde0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; color: #173b7a; font-weight: 700; font-size: 14px; }
			.dsf-full-dictionary .dsf-alphabet a.is-active { background: #173b7a; border-color: #173b7a; color: #fff; }
			.dsf-full-dictionary .dsf-meta { margin: 0 0 16px; font-size: 14px; color: #5a6982; }
			.dsf-full-dictionary ul { list-style: none; margin: 0; padding: 0; border: 1px solid #d3dbe9; border-radius: 14px; overflow: hidden; }
			.dsf-full-dictionary li { margin: 0; border-bottom: 1px solid #e6ecf5; background: #fff; }
			.dsf-full-dictionary li:last-child { border-bottom: 0; }
			.dsf-full-dictionary li a { display: block; padding: 13px 16px; color: #173b7a; text-decoration: none; font-weight: 600; }
			.dsf-full-dictionary li a:hover { background: #f5f8ff; }
			.dsf-full-dictionary .dsf-empty { border: 1px solid #d3dbe9; border-radius: 12px; background: #fff; padding: 18px; color: #3d4d68; }
			.dsf-full-dictionary .dsf-pagination { margin-top: 16px; display: flex; flex-wrap: wrap; gap: 6px; }
			.dsf-full-dictionary .dsf-pagination .page-numbers { display: inline-flex; min-width: 34px; height: 34px; align-items: center; justify-content: center; padding: 0 10px; border-radius: 8px; border: 1px solid #c2cde0; text-decoration: none; color: #173b7a; }
			.dsf-full-dictionary .dsf-pagination .page-numbers.current { background: #173b7a; border-color: #173b7a; color: #fff; }

			/* Logged-in users: hide the old teaser sections entirely. */
			body.page-id-33106 .elementor-element-6fabe9e,
			body.page-id-33106 .elementor-element-b684035,
			body.page-id-33106 .elementor-element-0c64dc5,
			body.page-id-33106 .elementor-element-fe6dfa7,
			body.page-id-33106 .elementor-element-b4e2681,
			body.page-id-33106 .elementor-element-1f6f9f3,
			body.page-id-33106 .elementor-element-f1339cd,
			body.page-id-33106 .elementor-element-a4c1839,
			body.page-id-33106 .elementor-element-336ff2b {
				display: none !important;
			}
		</style>

		<h2>Dicionário Completo (DSF)</h2>
		<p>Você está logado. Pesquise qualquer substância e abra a ficha completa.</p>

		<form class="dsf-search" method="get" action="<?php echo esc_url( $current_url ); ?>">
			<input type="search" name="dsf_q" value="<?php echo esc_attr( $search_term ); ?>" placeholder="Pesquise por nome, DCB, INN, CAS ou NCM" />
			<?php if ( '' !== $letter ) : ?>
				<input type="hidden" name="dsf_letter" value="<?php echo esc_attr( $letter ); ?>" />
			<?php endif; ?>
			<button type="submit">Buscar</button>
			<a href="<?php echo esc_url( $clear_url ); ?>">Limpar filtros</a>
		</form>

		<div class="dsf-alphabet"><?php echo wp_kses_post( implode( '', $alphabet_links ) ); ?></div>

		<div class="dsf-meta">
			<?php
			printf(
				/* translators: %d = number of records found */
				esc_html__( '%d resultados encontrados.', 'default' ),
				(int) $query->found_posts
			);
			?>
		</div>

		<?php if ( $query->have_posts() ) : ?>
			<ul>
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php
			$pagination = paginate_links(
				array(
					'base'      => add_query_arg( 'dsf_page', '%#%', remove_query_arg( 'dsf_page', $current_url ) ),
					'format'    => '',
					'current'   => $page,
					'total'     => max( 1, (int) $query->max_num_pages ),
					'type'      => 'array',
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
					'add_args'  => array(
						'dsf_q'      => $search_term,
						'dsf_letter' => $letter,
					),
				)
			);

			if ( ! empty( $pagination ) ) :
				?>
				<nav class="dsf-pagination" aria-label="Paginação do dicionário">
					<?php
					foreach ( $pagination as $page_link ) {
						echo wp_kses_post( $page_link );
					}
					?>
				</nav>
			<?php endif; ?>

		<?php else : ?>
			<div class="dsf-empty">Nenhuma substância encontrada com os filtros atuais.</div>
		<?php endif; ?>
	</section>
	<?php
	wp_reset_postdata();
	return (string) ob_get_clean();
}

/**
 * Swap teaser page content with full dictionary for logged users.
 *
 * @param string $content Original content.
 * @return string
 */
function dsf_dictionary_replace_content_for_logged_users( $content ) {
	if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( ! is_user_logged_in() || ! is_page( 'dicionario-dsf' ) ) {
		return $content;
	}

	return dsf_dictionary_render_logged_view() . $content;
}
add_filter( 'the_content', 'dsf_dictionary_replace_content_for_logged_users', 999 );
