/* jshint esversion: 6 */
/* global wp */
import PropTypes from 'prop-types'
import classnames from 'classnames'

const { Component } = wp.element
const { __ } = wp.i18n

class Select extends Component {
  constructor(props) {
    super( props )
    this.getGroupedOptions = this.getGroupedOptions.bind( this )
    this.getSimpleOptions = this.getSimpleOptions.bind( this )
    this.getEndGroupMarkup = this.getEndGroupMarkup.bind( this )
  }

  getGroupedOptions() {
    const { optionGroups } = this.props
    return Object.keys( optionGroups ).map( (groupKey) => {
      return (
        <optgroup label={optionGroups[groupKey].label} key={groupKey}>
          {Object.keys( optionGroups[groupKey].choices ).map( (optionKey) => {
            return (
              <option value={optionKey} key={optionKey}>
                {optionGroups[groupKey].choices[optionKey]}
              </option>
            )
          } )}
        </optgroup> )
    } )
  }

  getSimpleOptions() {
    const { optionGroups } = this.props
    return Object.keys( optionGroups ).map( (key) => {
      if ( optionGroups[key].length === 0 ) {
        return false
      }
      return (
        <option value={key} key={key}>
          {optionGroups[key]}
        </option>
      )
    } )
  }

  parseEndGroup() {
    const { optionGroups } = this.props
    return Object.keys( optionGroups ).map( (groupKey) => {
      return optionGroups[groupKey].map( (group) => {
        const { nicename, name, terms } = group
        let actualTerms = terms
        if ( !actualTerms || actualTerms.length < 1 ) {
          return false
        }
        if ( typeof actualTerms === 'object' ) {
          actualTerms = Object.values( actualTerms )
        }
        return (
          <optgroup
            label={`${nicename} (${groupKey} - ${name})`}
            key={`${groupKey}-${name}`}
          >
            {actualTerms.map( (term) => {
              return (
                <option
                  value={`${name}|${term.slug}`}
                  key={`${name}|${term.slug}|${groupKey}`}
                >{term.name}
                </option>
              )
            } )}
          </optgroup> )
      } )
    } )
  }

  getEndGroupMarkup() {
    const { noGroups, parseEndGroups } = this.props

    if ( parseEndGroups ) {
      return this.parseEndGroup()
    }
    if ( noGroups ) {
      return this.getSimpleOptions()
    }

    return this.getGroupedOptions()
  }

  render() {
    const { name, selected } = this.props
    return (
      <div
        className={classnames( [name, 'select-wrap'] )}
      >
        <select
          value={selected}
          className='conditional-select'
          onChange={(event) => {
            this.props.onChange( event.target.value )
          }}
        >
          {name !== 'condition' && (
            <option value=''>{__( 'Select', 'neve' )}</option> )}
          {this.getEndGroupMarkup()}
        </select>
      </div>
    )
  }
}

Select.propTypes = {
  optionGroups: PropTypes.array.isRequired,
  name: PropTypes.array.isRequired,
  onChange: PropTypes.func.isRequired,
  selected: PropTypes.string.isRequired,
  noGroups: PropTypes.bool,
  parseEndGroups: PropTypes.bool
}

export default Select
