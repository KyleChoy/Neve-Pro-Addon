/* jshint esversion: 6 */
/* global wp */
import RuleGroup from './RuleGroup'
import PropTypes from 'prop-types'

const { Component } = wp.element
const { __ } = wp.i18n
const { withSelect, withDispatch } = wp.data
const { compose } = wp.compose

class ConditionalSelectorComponent extends Component {
  constructor(props) {
    super( props )

    this.state = {
      reRenderFlag: false
    }

    this.updateValue = this.updateValue.bind( this )
    this.addRuleSet = this.addRuleSet.bind( this )
    this.removeRuleSet = this.removeRuleSet.bind( this )
    this.addRuleGroup = this.addRuleGroup.bind( this )
    this.removeRuleGroup = this.removeRuleGroup.bind( this )
    this.saveConditions = this.saveConditions.bind( this )
  }

  /**
   * Update all rules.
   *
   * @param value new value for ruleset dropdown.
   * @param type  type of the ruleset dropdown [root | condition | end ].
   * @param ruleSetIndex the index of the ruleset.
   * @param groupIndex the index of the rule group.
   */
  updateValue(value, type, ruleSetIndex, groupIndex) {
    const newValue = this.props.conditions

    newValue[groupIndex][ruleSetIndex][type] = value

    if ( type === 'root' && !value ) {
      newValue[groupIndex][ruleSetIndex].end = ''
    }

    this.saveConditions( newValue )
  }

  /**
   * @param ruleIndex the index of the ruleset.
   * @param groupIndex the index of the rule group.
   */
  addRuleSet(ruleIndex, groupIndex) {
    const newValue = this.props.conditions

    newValue[groupIndex].splice( ruleIndex + 1, 0,
      { root: '', condition: '===', end: '' } )

    this.saveConditions( newValue )
  }

  /**
   * @param ruleIndex the index of the ruleset.
   * @param groupIndex the index of the rule group.
   */
  removeRuleSet(ruleIndex, groupIndex) {
    const newValue = this.props.conditions

    newValue[groupIndex].splice( ruleIndex, 1 )

    this.saveConditions( newValue )
  }

  /**
   * @param groupIndex the index of the rule group.
   */
  addRuleGroup(groupIndex) {
    const newValue = this.props.conditions

    newValue.splice( groupIndex + 1, 0,
      [{ root: '', condition: '===', end: '' }] )

    this.saveConditions( newValue )
  }

  /**
   * @param groupIndex the index of the rule group.
   */
  removeRuleGroup(groupIndex) {
    const newValue = this.props.conditions

    newValue.splice( groupIndex, 1 )

    this.saveConditions( newValue )
  }

  saveConditions(newVal) {
    const { currentLayout } = this.props
    this.props.saveConditions( currentLayout, newVal )
    this.forceUpdate()
    const { control } = this.props

    // Mental gymnastics to trigger a change for this control.
    const oldConfig = control.setting.get()
    control.setting.set( {} )
    control.setting.set( {
      ...oldConfig,
      rules: {
        ...(oldConfig.rules || {} ),
        [currentLayout]: newVal
      }
    } )
  }

  render() {
    const { conditions, currentLayout } = this.props
    return (
      currentLayout === 'default' ||
        <div className='nv-conditional-selector-control'>
          <label className='customize-control-title'>{
            __( 'Display this header if', 'neve' )
          }
          </label>
          {conditions.map( (group, index) => {
            return (
              <RuleGroup
                key={index}
                group={group}
                isLast={index === conditions.length - 1}
                isFirst={index === 0}
                canAddMore={conditions.length < 3}
                onChange={(value, type, ruleSetIndex) => {
                  this.updateValue( value, type, ruleSetIndex, index )
                }}
                addRuleSet={(ruleIndex) => {
                  this.addRuleSet( ruleIndex, index )
                }}
                removeRuleSet={(ruleIndex) => {
                  this.removeRuleSet( ruleIndex, index )
                }}
                addRuleGroup={() => {
                  this.addRuleGroup( index )
                }}
                removeRuleGroup={() => {
                  this.removeRuleGroup( index )
                }}
              />
            )
          } )}
        </div>
    )
  }
}

ConditionalSelectorComponent.propTypes = {
  control: PropTypes.object.isRequired,
  conditions: PropTypes.array.isRequired,
  currentLayout: PropTypes.string.isRequired,
  saveConditions: PropTypes.func.isRequired
}

export default compose(
  withSelect( (select) => {
    const { getCurrentLayout, getConditions } = select( 'neve-store' )
    const currentLayout = getCurrentLayout()
    const conditions = getConditions()[currentLayout]
    return {
      conditions,
      currentLayout
    }
  } ),
  withDispatch( (dispatch) => {
    const { saveConditions } = dispatch( 'neve-store' )
    return {
      saveConditions: (slug, conditions) => {
        saveConditions( slug, conditions )
      }
    }
  } )
)( ConditionalSelectorComponent )
