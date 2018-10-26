'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import FormRender from './FormRender'
import FormValidation from './FormValidation'
import {fetchJson} from '../Component/fetchJson'
import {openPage} from '../Component/openPage'
import {translateMessage} from '../Component/MessageTranslator'

export default class FormControl extends Component {
    constructor(props) {
        super(props)

        this.form = props.form
        this.template = props.template
        this.translations = props.translations
        this.locale = props.locale
        this.otherProps = {...props}

        this.messages = []
        this.state = {
            messages: this.messages,
            form: this.form
        }
        this.elementChange = this.elementChange.bind(this)
        this.elementClick = this.elementClick.bind(this)
        this.deleteButtonHandler = this.deleteButtonHandler.bind(this)
        this.addButtonHandler = this.addButtonHandler.bind(this)
        this.closeButtonHandler = this.closeButtonHandler.bind(this)
        this.saveButtonHandler = this.saveButtonHandler.bind(this)
        this.getElementData = this.getElementData.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)
        this.getFormElementById = this.getFormElementById.bind(this)
        this.returnButtonHandler = this.returnButtonHandler.bind(this)

        this.formControl = {
            translations: this.translations,
            elementChange: this.elementChange,
            elementClick: this.elementClick,
            getElementData: this.getElementData,
            deleteButtonHandler: this.deleteButtonHandler,
            closeButtonHandler: this.closeButtonHandler,
            addButtonHandler: this.addButtonHandler,
            saveButtonHandler: this.saveButtonHandler,
            cancelMessage:  this.cancelMessage,
            getFormElementById: this.getFormElementById,
            returnButtonHandler: this.returnButtonHandler,
        }
        this.elementList = {}
    }

    elementChange(event, id){
        let element = this.getFormElementById(id)
        element.value = event.target.value
        element = FormValidation(element)
        if (element.errors.length > 0)
            element.errors.map(error => {
                this.setMessageByName(element.id, element.label + ' (' + element.value.toString() + '): ' + error)
            })
        else
            this.cancelMessageByName(element.id)
        this.setFormElement(element,this.form)
        this.setState({
            messages: this.messages,
            form: this.form
        })
    }

    // Used from Checkbox, radio, etc.
    elementClick(event, id){
        let element = this.getFormElementById(id)
        if (element.block_prefixes.includes('hillrange_toggle')){
            element.value = element.value === '1' ? '0' : '1'
            element.data = element.value === '1'
        }
        element = FormValidation(element)
        this.setFormElement(element,this.form)
        this.setState({
            messages: this.messages,
            form: this.form
        })
    }

    deleteButtonHandler(button){
        const element = button.row
        const eid = parseInt(element.name)
        const collectionId = element.id.replace('_' + eid, '')
        let collection = this.getFormElementById(collectionId, this.form)
        const prototype = {...collection.prototype}
        let counter = 0
        let children = []
        if (typeof collection.children === 'object'){
            Object.keys(collection.children).map(key => {
                if (parseInt(key) !== parseInt(eid)) {
                    let element = {...collection.children[key]}
                    let was = {}
                    was.id = element.id
                    was.full_name = element.full_name
                    was.name = element.name
                    let now = {}
                    now.id = prototype.id.replace('__name__', counter)
                    now.full_name = prototype.full_name.replace('__name__', counter)
                    element.name = prototype.name.replace('__name__', counter)
                    element.full_name = prototype.full_name.replace('__name__', counter)
                    element.id = prototype.id.replace('__name__', counter)
                    element.name = counter.toString()
                    element.label = prototype.label.replace('__name__', counter)
                    element = this.updateCollectionDetails(element,was,now)
                    children[counter++] = element
                }
            })
        } else {
            collection.children.map((element, key) => {
                if (parseInt(key) !== parseInt(eid)) {
                    let was = {}
                    was.id = element.id
                    was.full_name = element.full_name
                    was.name = element.name
                    let now = {}
                    now.id = prototype.id.replace('__name__', counter)
                    now.full_name = prototype.full_name.replace('__name__', counter)
                    element.name = prototype.name.replace('__name__', counter)
                    element.full_name = prototype.full_name.replace('__name__', counter)
                    element.id = prototype.id.replace('__name__', counter)
                    element.name = counter.toString()
                    element.label = prototype.label.replace('__name__', counter)
                    element = this.updateCollectionDetails(element,was,now)
                    children[counter++] = element
                }
            })
        }

        this.elementList = {}
        collection.children = children
        this.setFormElement(collection, this.form)

        this.setState({
            form: this.form,
            messages: this.messages,
        })
    }

    updateCollectionDetails(element,was,now)
    {
        element.children.map(child => {
            child.full_name = child.full_name.replace(was.full_name,now.full_name)
            child.id = child.id.replace(was.id,now.id)
            this.updateCollectionDetails(child,was,now)
         })
        return element
    }

    addButtonHandler(button){
        const id = button.id.replace('_add','')
        let collection = this.getFormElementById(id)
        const key = collection.children.length
        let prototype = {...collection.prototype}
        prototype = this.setCollectionMemberKey(prototype, key)

        let children = collection.children
        children[key] = prototype
        collection.children = children

        this.setFormElement(collection, this.form)
        this.setState({
            form: this.form,
            messages: this.messages,
        })
    }

    getFormElementById(id, refresh = false) {
        if (refresh === true)
            this.elementList = {}
        if (typeof this.elementList[id] === 'undefined')
            this.elementList = this.buildElementList({})
        return this.elementList[id]
    }

    buildElementList(list, form = this.form) {
        list[form.id] = form
        form.children.map(child => {
            this.buildElementList(list,child)
        })
        return list
    }

    setCollectionMemberKey(prototype, key)
    {
        let vars = {...prototype}
        vars.children = prototype.children.map(child => {
            return this.setCollectionMemberKey(child,key)
        })

        vars.full_name = vars.full_name.replace('__name__', key)
        vars.id = vars.id.replace('__name__', key)
        vars.name = vars.name.replace('__name__', key)
        vars.label = vars.label.replace('__name__', key)

        return vars;
    }

    setFormElement(element, form) {
        if (element.id === form.id)
        {
            form = {...element}
            return form
        }
        form.children.map(child => {
            this.setFormElement(element, child)
        })
    }

    getElementData(id){
        let element = this.getFormElementById(id)

        if (typeof element === 'undefined' || element.id !== id) {
            console.error('The element returned does not match the requested element named: ' + id)
            console.log(this.form)
            return ''
        }

        if ((element.value === '' || element.value === 'undefined' || element.value === null) && element.value !== element.data)
        {
            element.value = element.data
            this.elementList[element.id] = element
        }
        return (typeof element.value === 'undefined' || element.value === null) ? '' : element.value
    }

    buildFormData(data, form) {
        if (form.children.length > 0){
            form.children.map(child => {
                data[child.name] = this.buildFormData({}, child)
                this.setMessageByElementErrors(child)
            })
            return data
        } else {
            this.setMessageByElementErrors(form)
            return form.value
        }
    }

    returnButtonHandler(options)
    {
        openPage(options.url)
    }

    closeButtonHandler()
    {
        window.close()
    }

    saveButtonHandler() {
        this.data = this.buildFormData({}, this.form)
        if (this.messages.length === 0) {
            fetchJson(
                this.template.form.url,
                {method: this.template.form.method, body: JSON.stringify(this.data)},
                this.locale)
                .then(data => {
                    this.elementList = {}
                    this.messages = this.messages.concat(data.messages)
                    this.form = data.form
                    this.setState({
                        form: this.form,
                        messages: this.messages
                    })
                }).catch(error => {
                console.error('Error: ', error)
                this.messages.push({level: 'danger', message: error})
                this.setState({
                    form: this.form,
                    messages: this.messages
                })
            })
        } else {
            const message = {level: 'danger', message: translateMessage(this.translations, 'All errors must be cleared before the form can be saved!')}
            this.messages.push(message)
            this.setState({
                form: this.form,
                messages: this.messages,
            })
        }
    }

    cancelMessage(id) {
        this.messages.splice(id,1)
        this.setState({
            messages: this.messages,
            form: this.form,
        })
    }

    cancelMessageByName(name) {
        Object.keys(this.messages).map(key => {
            const message = this.messages[key]
            if (typeof message.name !== 'undefined' && message.name === name)
                this.messages.splice(key,1)
        })
    }

    setMessageByName(name, error) {
        this.cancelMessageByName(name)
        let message = {name: name, level: 'danger', message: error}
        this.messages.push(message)
    }

    setMessageByElementErrors(element){
        element = FormValidation(element)
        element.errors.map(error => {
            console.log(element.value)
            this.setMessageByName(element.id, element.label + ' (' + (!(!element.value || /^\s*$/.test(element.value)) ? element.value.toString() : '{empty}') + '): ' + error)
        })
    }

    render() {
        return (
            <FormRender
                template={this.template}
                form={{...this.state.form}}
                messages={this.messages}
                {...this.formControl}
                {...this.otherProps}
            />
        )
    }
}

FormControl.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    locale: PropTypes.string.isRequired,
}
