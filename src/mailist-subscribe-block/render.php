<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
$button_color = isset( $attributes['button_color'] ) ? esc_attr( $attributes['button_color'] ) : '#38ff00';
$font_size = isset( $attributes['font_size'] ) ? intval( $attributes['font_size'] ) : 16;
$label_email = isset( $attributes['label_email'] ) ? esc_html( $attributes['label_email'] ) : esc_html__( 'Email:', 'mailist-subscribe-block' );
$label_button = isset( $attributes['label_button'] ) ? esc_html( $attributes['label_button'] ) : esc_html__( 'Subscribe', 'mailist-subscribe-block' );
$label_consent = isset( $attributes['label_consent'] ) ? esc_html( $attributes['label_consent'] ) : esc_html__( 'I consent to having my email stored and used to receive updates. See our ', 'mailist-subscribe-block' );
$privacy_policy_url = isset( $attributes['privacy_policy_url'] ) ? esc_url( $attributes['privacy_policy_url'] ) : '/privacy-policy';
$label_privacy_link = isset( $attributes['label_privacy_link'] ) ? esc_html( $attributes['label_privacy_link'] ) : esc_html__( 'Privacy Policy', 'mailist-subscribe-block' );
$label_success = isset( $attributes['label_success'] ) ? esc_html( $attributes['label_success'] ) : esc_html__( 'Subscription successful!', 'mailist-subscribe-block' );
$label_error = isset( $attributes['label_error'] ) ? esc_html( $attributes['label_error'] ) : esc_html__( 'An error occurred. Please try again.', 'mailist-subscribe-block' );
$label_already = isset( $attributes['label_already'] ) ? esc_html( $attributes['label_already'] ) : esc_html__( 'This user might have already subscribed', 'mailist-subscribe-block' );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <!-- Subscription Form -->
    <form onsubmit="return subscribe(event);" style="font-size: <?php echo $font_size; ?>px;">
        <label for="email"><?php echo $label_email; ?></label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="gdpr-consent" style="display: flex; align-items: center; font-size: 0.95em;">
            <input type="checkbox" id="gdpr-consent" name="gdpr_consent" required style="margin-right: 0.5em;">
            <?php echo $label_consent; ?><a href="<?php echo $privacy_policy_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $label_privacy_link; ?></a>.
        </label>
        <br>
        <button type="submit" id="mailist-submit-btn" style="background-color: <?php echo $button_color; ?>;">
            <?php echo $label_button; ?>
        </button>
        <div id="mailist-notification" class="mailist-notification" style="margin-top:1em;"></div>
    </form>
</div>

<script>
/**
 * Handles the subscribe form submission.
 *
 * Parameters
 * ----------
 * event : Event
 *     The form submit event.
 *
 * Returns
 * -------
 * false
 *     Always returns false to prevent default form submission.
 */
function subscribe(event) {
    event.preventDefault();
    var form = event.target;
    var email = form.querySelector('#email').value;
    var consent = form.querySelector('#gdpr-consent').checked;
    var listId = "<?php echo esc_html( $attributes['listId'] ); ?>";
    var btn = form.querySelector('#mailist-submit-btn');
    var notification = form.querySelector('#mailist-notification');
    if (!consent) {
        notification.textContent = <?php echo json_encode($label_error); ?>;
        notification.className = 'mailist-notification error';
        return false;
    }
    btn.disabled = true;
    btn.textContent = 'Subscribing...';
    notification.textContent = '';
    notification.className = 'mailist-notification';
    fetch('https://mailist.luova.club/signup', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, list_id: listId, gdpr_consent: consent })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            notification.textContent = <?php echo json_encode($label_success); ?>;
            notification.className = 'mailist-notification success';
        } else if (data.status === 409 || data.code === 409) {
            notification.textContent = <?php echo json_encode($label_already); ?>;
            notification.className = 'mailist-notification error';
        } else {
            notification.textContent = (data.message && typeof data.message === 'string') ? data.message : <?php echo json_encode($label_error); ?>;
            notification.className = 'mailist-notification error';
        }
    })
    .catch(function() {
        notification.textContent = <?php echo json_encode($label_error); ?>;
        notification.className = 'mailist-notification error';
    })
    .finally(function() {
        btn.disabled = false;
        btn.textContent = <?php echo json_encode($label_button); ?>;
    });
    return false;
}
</script>

<style>
    /* Styling the alert box */
    .alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        z-index: 1000;
        transition: opacity 0.5s ease;
    }

    .alert.success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .alert.fade-out {
        opacity: 0;
    }

    /* Button transition */
    button {
        transition: background-color 0.3s ease;
    }

    button:disabled {
        background-color: #ccc;
    }

    .mailist-notification {
        margin-top: 1em;
        font-size: 1em;
        padding: 0.75em 1em;
        border-radius: 4px;
        background: #f8f9fa;
        color: #333;
        border: 1px solid #e0e0e0;
        min-height: 1.5em;
    }
    .mailist-notification.success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .mailist-notification.error {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
</style>

