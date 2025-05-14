import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ColorPalette, FontSizePicker } from '@wordpress/components';
import { useState } from '@wordpress/element';

const colors = [
	{ name: 'Green', color: '#38ff00' },
	{ name: 'Blue', color: '#0073e6' },
	{ name: 'Red', color: '#e60023' },
	{ name: 'Gray', color: '#cccccc' },
	{ name: 'Black', color: '#222222' },
];
const fontSizes = [ 14, 16, 18, 20, 24, 28 ];

registerBlockType( 'create-block/mailist-subscribe-block', {
	edit: ( { attributes, setAttributes } ) => {
		const { listId, button_color, font_size, label_email, label_button, label_consent, privacy_policy_url, label_privacy_link, label_success, label_error, label_already } = attributes;

		const [notification, setNotification] = useState("");
		const [notificationType, setNotificationType] = useState("");
		const [isProcessing, setIsProcessing] = useState(false);

		const handleSubmit = (e) => {
			e.preventDefault();
			const email = e.target.email.value;
			const consent = e.target["gdpr-consent"].checked;
			if (!consent) {
				setNotification(label_error || 'You must consent to data storage to subscribe.');
				setNotificationType('error');
				return;
			}
			setIsProcessing(true);
			setNotification("");
			setNotificationType("");
			fetch('https://mailist.luova.club/signup', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email, list_id: listId, gdpr_consent: consent })
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.success) {
						setNotification(label_success || 'Subscription successful!');
						setNotificationType('success');
					} else if (data.status === 409 || data.code === 409) {
						setNotification(label_already || __('This user might have already subscribed', 'mailist-subscribe-block'));
						setNotificationType('error');
					} else {
						setNotification((data.message && typeof data.message === 'string') ? data.message : (label_error || 'Subscription failed.'));
						setNotificationType('error');
					}
				})
				.catch(() => {
					setNotification(label_error || 'An error occurred. Please try again.');
					setNotificationType('error');
				})
				.finally(() => {
					setIsProcessing(false);
				});
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'mailist-subscribe-block' ) }>
						<TextControl
							label={ __( 'List ID', 'mailist-subscribe-block' ) }
							value={ listId }
							onChange={ ( value ) => setAttributes( { listId: value } ) }
							/>
						<TextControl
							label={ __( 'Email Label', 'mailist-subscribe-block' ) }
							value={ label_email }
							onChange={ ( value ) => setAttributes( { label_email: value } ) }
						/>
						<TextControl
							label={ __( 'Button Text', 'mailist-subscribe-block' ) }
							value={ label_button }
							onChange={ ( value ) => setAttributes( { label_button: value } ) }
						/>
						<TextControl
							label={ __( 'Consent Text', 'mailist-subscribe-block' ) }
							value={ label_consent }
							onChange={ ( value ) => setAttributes( { label_consent: value } ) }
							/>
						<TextControl
							label={ __( 'Privacy Policy URL', 'mailist-subscribe-block' ) }
							value={ privacy_policy_url }
							onChange={ ( value ) => setAttributes( { privacy_policy_url: value } ) }
							/>
						<TextControl
							label={ __( 'Privacy Link Text', 'mailist-subscribe-block' ) }
							value={ label_privacy_link }
							onChange={ ( value ) => setAttributes( { label_privacy_link: value } ) }
						/>
						<TextControl
							label={ __( 'Success Message', 'mailist-subscribe-block' ) }
							value={ label_success }
							onChange={ ( value ) => setAttributes( { label_success: value } ) }
						/>
						<TextControl
							label={ __( 'Error Message', 'mailist-subscribe-block' ) }
							value={ label_error }
							onChange={ ( value ) => setAttributes( { label_error: value } ) }
							/>
						<TextControl
							label={ __( 'Already Subscribed Message', 'mailist-subscribe-block' ) }
							value={ label_already }
							onChange={ ( value ) => setAttributes( { label_already: value } ) }
						/>
					</PanelBody>
					<PanelBody title={ __( 'Appearance', 'mailist-subscribe-block' ) } initialOpen={ false }>
						<p>{ __( 'Button Color', 'mailist-subscribe-block' ) }</p>
						<ColorPalette
							colors={ colors }
							value={ button_color }
							onChange={ ( color ) => setAttributes( { button_color: color } ) }
						/>
						<p>{ __( 'Font Size', 'mailist-subscribe-block' ) }</p>
						<FontSizePicker
							fontSizes={ fontSizes.map( size => ( { name: `${ size }px`, slug: `${ size }`, size } ) ) }
							value={ font_size }
							onChange={ ( size ) => setAttributes( { font_size: size } ) }
						/>
					</PanelBody>
				</InspectorControls>
				<div { ...useBlockProps() }>
					<form
						className="mailist-subscribe-form"
						data-list-id={ listId }
						style={{ fontSize: font_size ? `${font_size}px` : undefined }}
						onSubmit={handleSubmit}
					>
						<label htmlFor="email">
							{ label_email || __( 'Email:', 'mailist-subscribe-block' ) }
						</label>
						<input type="email" id="email" name="email" required />
						<br />
						<label htmlFor="gdpr-consent" style={{ display: 'flex', alignItems: 'center', fontSize: '0.95em' }}>
							<input type="checkbox" id="gdpr-consent" name="gdpr_consent" required style={{ marginRight: '0.5em' }} />
							{ label_consent || __( 'I consent to having my email stored and used to receive updates. See our ', 'mailist-subscribe-block' ) }
							<a href={ privacy_policy_url || '/privacy-policy' } target="_blank" rel="noopener noreferrer">{ label_privacy_link || __( 'Privacy Policy', 'mailist-subscribe-block' ) }</a>.
						</label>
						<br />
						{notification && (
							<div className={`mailist-notification ${notificationType}`}>{notification}</div>
						)}
						<button type="submit" style={{ backgroundColor: button_color || '#38ff00' }} disabled={isProcessing}>
							{ isProcessing ? __( 'Subscribing...', 'mailist-subscribe-block' ) : (label_button || __( 'Subscribe', 'mailist-subscribe-block' )) }
						</button>
					</form>
				</div>
			</>
		);
	},
	attributes: {
		listId: {
			type: 'string',
			default: '67617934a04a83ee026352d3',
		},
		button_color: {
			type: 'string',
			default: '#38ff00',
		},
		font_size: {
			type: 'number',
			default: 16,
		},
		label_email: {
			type: 'string',
			default: 'Email:',
		},
		label_button: {
			type: 'string',
			default: 'Subscribe',
		},
		label_consent: {
			type: 'string',
			default: 'I consent to having my email stored and used to receive updates. See our ',
		},
		privacy_policy_url: {
			type: 'string',
			default: '/privacy-policy',
		},
		label_privacy_link: {
			type: 'string',
			default: 'Privacy Policy',
		},
		label_success: {
			type: 'string',
			default: 'Subscription successful!',
		},
		label_error: {
			type: 'string',
			default: 'Subscription failed.',
		},
		label_already: {
			type: 'string',
			default: 'This user might have already subscribed',
		},
	},
} );