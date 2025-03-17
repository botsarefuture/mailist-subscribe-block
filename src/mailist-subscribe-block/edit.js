import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<TextControl
				label={ __( 'Mailist List ID', 'mailist-subscribe-block' ) }
				value={ attributes.listId }
				onChange={ ( value ) => setAttributes( { listId: value } ) }
				help={ __(
					'Enter the email list ID for subscriptions.',
					'mailist-subscribe-block'
				) }
			/>
			<p>
				{ __(
					'Mailist Subscribe Block – hello from the editor!',
					'mailist-subscribe-block'
				) }
			</p>
		</div>
	);
}
