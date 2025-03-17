import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

registerBlockType( 'create-block/mailist-subscribe-block', {
	edit: ( { attributes, setAttributes } ) => {
		const { listId } = attributes;

		return (
			<>
				<InspectorControls>
					<PanelBody
						title={ __( 'Settings', 'mailist-subscribe-block' ) }
					>
						<TextControl
							label={ __( 'List ID', 'mailist-subscribe-block' ) }
							value={ listId }
							onChange={ ( value ) =>
								setAttributes( { listId: value } )
							}
						/>
					</PanelBody>
				</InspectorControls>
				<div { ...useBlockProps() }>
					<form
						className="mailist-subscribe-form"
						data-list-id={ listId }
					>
						<label htmlFor="email">
							{ __( 'Email:', 'mailist-subscribe-block' ) }
						</label>
						<input type="email" id="email" name="email" required />
						<br />
						<br />
						<button type="submit">
							{ __( 'Subscribe', 'mailist-subscribe-block' ) }
						</button>
					</form>
				</div>
			</>
		);
	},
} );
