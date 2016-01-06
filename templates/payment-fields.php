<?php
/**
 * The Template for displaying the credit card form on the checkout page
 *
 * Override this template by copying it to yourtheme/woocommerce/p4wc/payment-fields.php
 *
 * @author      Stephen Zuniga
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $p4wc;

// Add notification to the user that this will fail miserably if they attempt it.
echo '<noscript>';
    printf( __( '%s payment does not work without Javascript. Please enable Javascript or use a different payment method.', 'stripe-for-woocommerce' ), $p4wc->settings['title'] );
echo '</noscript>';

// Payment method description
if ( $p4wc->settings['description'] ) {
    echo '<p class="p4wc-description">' .  $p4wc->settings['description'] . '</p>';
}

// Get user database object
$stripe_customer_info = get_user_meta( get_current_user_id(), $p4wc->settings['stripe_db_location'], true );

if ( is_user_logged_in() && ! empty( $stripe_customer_info['cards'] ) && $p4wc->settings['saved_cards'] === 'yes' ) :

    // Add option to use a saved card
    foreach ( $stripe_customer_info['cards'] as $i => $credit_card ) :
        $checked = ( $stripe_customer_info['default_card'] == $credit_card['id'] ) ? ' checked' : '';

        if ( $i === 0 && $stripe_customer_info['default_card'] === '' ) {
            $checked = ' checked';
        }
    ?>

        <input type="radio" id="stripe_card_<?php echo $i; ?>" name="p4wc_card" value="<?php echo $i; ?>"<?php echo $checked; ?>>
        <label for="stripe_card_<?php echo $i; ?>"><?php printf( __( 'Card ending with %s (%s/%s)', 'stripe-for-woocommerce' ), $credit_card['last4'], $credit_card['exp_month'], $credit_card['exp_year'] ); ?></label><br>

    <?php endforeach; ?>

    <input type="radio" id="new_card" name="p4wc_card" value="new">
    <label for="new_card"><?php _e( 'Use a new credit card', 'stripe-for-woocommerce' ); ?></label>

<?php endif; ?>
