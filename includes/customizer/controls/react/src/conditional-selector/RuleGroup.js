/* jshint esversion: 6 */
/* global wp */
import PropTypes from 'prop-types'
import RuleSet from './RuleSet'

const { Component, Fragment } = wp.element
const { Button } = wp.components
const { __ } = wp.i18n

class RuleGroup extends Component {
  constructor(props) {
    super( props )
    this.updateValues = this.updateValues.bind( this )
  }

  /**
   *
   * @param value value of dropdown.
   * @param type type of dropdown [ root | condition | end ]
   * @param ruleSetIndex index of the ruleset.
   */
  updateValues(value, type, ruleSetIndex) {
    this.props.onChange( value, type, ruleSetIndex )
  }

  render() {
    const { group, isFirst, isLast, canAddMore } = this.props
    return (
      <Fragment>
        <div className='rule-group'>
          {group.map( (ruleset, index) =>
            (
              <RuleSet
                key={index}
                ruleset={ruleset}
                isLast={index === group.length - 1}
                isFirst={index === 0}
                canAddMore={group.length < 3}
                updateRoot={(value) => this.updateValues( value,
                  'root',
                  index )}
                updateCondition={(value) => this.updateValues(
                  value,
                  'condition', index )}
                updateEnd={(value) => this.updateValues( value,
                  'end', index )}
                addRuleSet={() => {
                  this.props.addRuleSet( index )
                }}
                removeRuleSet={() => {
                  this.props.removeRuleSet( index )
                }}
              />
            )
          )}
          <div className='actions'>
            <Button
              isDefault
              isSmall
              disabled={!canAddMore}
              className='add-group'
              onClick={() => {
                this.props.addRuleGroup()
              }}
            >
              {__( 'Add Rule Group', 'neve' )}
            </Button>
            {( !( isLast && isFirst ) ) &&
              <Button
                isLink
                isDestructive
                isSmall
                className='remove-group'
                onClick={() => {
                  this.props.removeRuleGroup()
                }}
              >
                {__( 'Remove Rule Group', 'neve' )}
              </Button>}
          </div>
        </div>
        {!isLast &&
          <span className='chainer'>{__( 'or', 'neve' )}</span>}
      </Fragment>
    )
  }
}

RuleGroup.propTypes = {
  group: PropTypes.array.isRequired,
  isLast: PropTypes.bool.isRequired,
  isFirst: PropTypes.bool.isRequired,
  canAddMore: PropTypes.bool.isRequired,
  onChange: PropTypes.func.isRequired,
  addRuleSet: PropTypes.func.isRequired,
  removeRuleSet: PropTypes.func.isRequired,
  addRuleGroup: PropTypes.func.isRequired,
  removeRuleGroup: PropTypes.func.isRequired
}

export default RuleGroup
