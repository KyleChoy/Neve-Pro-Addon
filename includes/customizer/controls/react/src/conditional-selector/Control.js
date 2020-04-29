/* jshint esversion: 6 */
/* global wp */
import ConditionalHeader from './ConditionalHeader'

const { render } = wp.element

export const ConditionalSelectorControl = wp.customize.Control.extend( {
  renderContent: function renderContent() {
    render(
      <ConditionalHeader control={this} />
      , this.container[0] )
  }
} )
