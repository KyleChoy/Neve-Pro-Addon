/* jshint esversion: 6 */
/* global NeveProReactCustomize, wp */
import PropTypes from 'prop-types'
import Select from './Select'

const { Component, Fragment } = wp.element
const { Button, Tooltip, Dashicon } = wp.components
const { __ } = wp.i18n

class RuleSet extends Component {
  constructor(props) {
    super( props )
    this.state = {}
    this.renderRuleset = this.renderRuleset.bind( this )
    this.getEndOptions = this.getEndOptions.bind( this )
  }

  renderRuleset() {
    const { root, condition, end } = this.props.ruleset
    const { isLast, isFirst, canAddMore } = this.props
    const options = NeveProReactCustomize.conditionalRules
    const conditionOptions = {
      '===': __( 'is', 'neve' ),
      '!==': __( 'is not', 'neve' )
    }
    const parseAsEndGroup = [
      'post_taxonomy',
      'archive_term',
      'archive_taxonomy'].includes( root )
    return (
      <div className='rule-set'>
        <Select
          name='root'
          className='root'
          optionGroups={options.root}
          selected={root}
          onChange={(value) => this.props.updateRoot( value )}
        />
        {root && (
          <Fragment>
            <Select
              name='condition'
              className='condition'
              optionGroups={conditionOptions}
              selected={condition}
              noGroups
              onChange={(value) => this.props.updateCondition( value )}
            />
            <Select
              name='end'
              className='end'
              optionGroups={this.getEndOptions()}
              selected={end}
              parseEndGroups={parseAsEndGroup}
              noGroups={!parseAsEndGroup}
              onChange={(value) => this.props.updateEnd( value )}
            />
          </Fragment> )}
        <Tooltip text={__( 'Remove Rule', 'neve' )}>
          <Button
            isLink
            isDestructive
            className='remove-rule'
            disabled={isLast && isFirst}
            onClick={() => { this.props.removeRuleSet() }}
          >
            <Dashicon icon='no' />
          </Button>
        </Tooltip>
        <Tooltip text={__( 'Add Rule', 'neve' )}>
          <Button
            isLink
            className='add-rule'
            disabled={!canAddMore}
            onClick={() => {
              this.props.addRuleSet()
            }}
          >
            <Dashicon icon='plus' />
          </Button>
        </Tooltip>
        {!isLast && <span className='chainer'>{__( 'and', 'neve' )}</span>}
      </div>
    )
  }

  getEndOptions() {
    const { root } = this.props.ruleset

    if ( !root ) return []

    const { map, end } = NeveProReactCustomize.conditionalRules

    const endRuleSlug = Object.keys( map )
      .filter( (key) => map[key].includes( root ) )
    return end[endRuleSlug[0]]
  }

  render() {
    return (
      <Fragment>
        {this.renderRuleset()}
      </Fragment>
    )
  }
}

RuleSet.propTypes = {
  ruleset: PropTypes.array.isRequired,
  isLast: PropTypes.bool.isRequired,
  isFirst: PropTypes.bool.isRequired,
  canAddMore: PropTypes.bool.isRequired,
  updateRoot: PropTypes.func.isRequired,
  updateCondition: PropTypes.func.isRequired,
  updateEnd: PropTypes.func.isRequired,
  removeRuleSet: PropTypes.func.isRequired,
  addRuleSet: PropTypes.func.isRequired
}

export default RuleSet
