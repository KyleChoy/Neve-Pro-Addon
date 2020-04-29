/* jshint esversion: 6 */
/* global wp */
import { ConditionalSelectorControl } from './conditional-selector/Control'
import './style.scss'

wp.customize.controlConstructor.neve_context_conditional_selector = ConditionalSelectorControl
