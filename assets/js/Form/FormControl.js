'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import FormRender from './FormRender'
import SetFormElementValue from './SetFormElementValue'
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
        delete this.otherProps.form
        delete this.otherProps.template
        delete this.otherProps.translations
        delete this.otherProps.locale

        this.messages = []
        if (!(!this.form.errors || /^\s*$/.test(this.form.errors)))
            this.messages = this.form.errors.map(message => {
                return {level: 'danger', message: message}
            })
        this.data = {}
        this.state = {
            messages: this.messages,
            form: this.form,
            template: this.template,
        }

        this.elementChange = this.elementChange.bind(this)
        this.elementClick = this.elementClick.bind(this)
        this.deleteButtonHandler = this.deleteButtonHandler.bind(this)
        this.addButtonHandler = this.addButtonHandler.bind(this)
        this.closeButtonHandler = this.closeButtonHandler.bind(this)
        this.saveButtonHandler = this.saveButtonHandler.bind(this)
        this.getElementData = this.getElementData.bind(this)
        this.getElementId = this.getElementId.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)
        this.getFormElementById = this.getFormElementById.bind(this)
        this.returnButtonHandler = this.returnButtonHandler.bind(this)
        this.miscButtonHandler = this.miscButtonHandler.bind(this)
        this.callUrl = this.callUrl.bind(this)


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
            miscButtonHandler: this.miscButtonHandler,
            getElementId: this.getElementId,
            callUrl: this.callUrl,
        }
        this.elementList = {}
    }

    callUrl(event, options){
        this.handleURLCall(options.url,options.url_options,options.url_type, {})
    }

    miscButtonHandler(options, event){
        event.stopPropagation()
        this.handleURLCall(options.url,options.url_options,options.url_type, {})
    }

    elementChange(event, id, type){
        if (id !== 'ignore_me') {
            let element = this.getFormElementById(id)
            element = SetFormElementValue(event, element, type)

            if (typeof element.attr.onChange !== 'undefined') {
                this.followUrl(element.attr.onChange, element)
            }

            if (element.errors.length > 0)
                element.errors.map(error => {
                    this.setMessageByName(element.id, (element.label ? element.label : element.name) + + ' (' + (!(!element.value || /^\s*$/.test(element.value)) ? JSON.stringify(element.value) : '{empty}') + '): ' + error)
                })
            else
                this.cancelMessageByName(element.id)

            this.setFormElement(element, this.form)
            this.setState({
                messages: this.messages,
                form: this.form,
                template: this.template,
            })
        }
    }

    // Used from checkbox, radio, toggle, etc.
    elementClick(event, id, type){
        if (id !== 'ignore_me') {
            let element = this.getFormElementById(id)
            element = SetFormElementValue(event, element, type)
            this.setFormElement(element, this.form)
            this.setState({
                messages: this.messages,
                template: this.template,
                form: this.form,
            })
        }
    }

    deleteButtonHandler(button) {
        let url = button.url
        if (!(!url || /^\s*$/.test(url))) {
            let found = this.handleURLCall(url, button.url_options, button.url_type, button.row)
            if (found) return
        }
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
            template: this.template,
        })
    }

    followUrl(details,element) {
        const url = details.url
        const options = (details.url_options && typeof details.url_options === 'object') ? details.url_options : {}
        const type = (details.url_type && typeof details.url_type === 'string') ? details.url_type : 'redirect'
        this.handleURLCall(url,options,type,element)
    }

    handleURLCall(url,options,type,element) {
        if (typeof options !== 'object')
            options = {}
        let found = true
        Object.keys(options).map(search => {
            let replace = element[options[search]]
            if (search === '{id}' && (!replace || /^\s*$/.test(replace)))
                replace = 'Add'
            url = url.replace(search, replace)
            if (replace === undefined || replace === null)
                found = false
        })
        if (!found) return false
        if (type === 'redirect') {
            openPage(url, {method: 'GET'}, this.locale)
        } else {
            fetchJson(url, {method: 'GET'}, this.locale)
                .then(data => {
                    this.elementList = {}
                    this.messages = this.messages.concat(data.messages)
                    this.form = data.form
                    if (!(!data.template || /^\s*$/.test(data.template)))
                        this.template = data.template
                    this.setState({
                        form: this.form,
                        messages: this.messages,
                        template: this.template,
                    })
                }).catch(error => {
                    console.error('Error: ', error)
                    this.messages.push({level: 'danger', message: error})
                    this.setState({
                        form: this.form,
                        messages: this.messages,
                        template: this.template,
                    })
            })
        }
        return true
    }

    updateCollectionDetails(element,was,now) {
        element.children.map(child => {
            child.full_name = child.full_name.replace(was.full_name,now.full_name)
            child.id = child.id.replace(was.id,now.id)
            this.updateCollectionDetails(child,was,now)
         })
        return element
    }

    addButtonHandler(button) {
        let url = button.url
        if (!(!url || /^\s*$/.test(url)))
            this.handleURLCall(url, button.url_options, button.url_type, {})
        else {

            const id = button.id.replace('_add', '')
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
                template: this.template,
            })
        }
    }


    getElementId(id) {
        return id
    }

    getFormElementById(id, refresh = false) {
        if (refresh === true)
            this.elementList = {}
        if (typeof this.elementList[id] === 'undefined')
            this.elementList = this.buildElementList({}, this.form)
        return this.elementList[id]
    }

    buildElementList(list, form) {
        list[form.id] = form
        form.children.map(child => {
            this.buildElementList(list,child)
        })
        return list
    }

    setCollectionMemberKey(prototype, key) {
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

    getElementData(id) {
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

        if (element.block_prefixes.includes('choice'))
        {
            if (typeof element.value === 'object' && element.value.length === undefined)
            {
                if (element.placeholder === null && typeof element.choices[0] !== 'undefined')
                    element.value = element.data = element.choices[0].value
            }
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

    isOKtoSave() {
        if (this.messages.length === 0)
            return true
        let ok = true
        this.messages.map(message => {
            if (['warning','danger'].includes(message.level))
                ok = false
        })
        return ok
    }

    saveButtonHandler() {
        this.data = this.buildFormData({}, this.form)
        if (this.isOKtoSave()) {
            this.messages = []
            fetchJson(
                this.template.form.url,
                {method: this.template.form.method, body: JSON.stringify(this.data)},
                this.locale)
                .then(data => {
                    this.elementList = {}
                    this.messages = this.messages.concat(data.messages)
                    this.form = data.form
                    if (!(!data.template || /^\s*$/.test(data.template)))
                        this.template = data.template
                    this.setState({
                        form: this.form,
                        messages: this.messages,
                        template: this.template,
                    })
                }).catch(error => {
                console.error('Error: ', error)
                this.messages.push({level: 'danger', message: error})
                this.setState({
                    form: this.form,
                    messages: this.messages,
                    template: this.template,
                })
            })
        } else {
            const message = {level: 'dark', message: translateMessage(this.translations, 'All errors must be cleared before the form can be saved!')}
            this.messages.push(message)
            this.setState({
                form: this.form,
                messages: this.messages,
                template: this.template,
            })
        }
    }

    cancelMessage(id) {
        this.messages.splice(id,1)
        this.setState({
            messages: this.messages,
            form: this.form,
            template: this.template,
        })
    }

    cancelMessageByName(name) {
        Object.keys(this.messages).map(key => {
            const message = this.messages[key]
            if (typeof message !== 'undefined')
                if (typeof message.name !== 'undefined')
                    if (message.name === name)
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
            this.setMessageByName(element.id, (element.label ? element.label : element.name ) + ' (' + (!(!element.value || /^\s*$/.test(element.value)) ? JSON.stringify(element.value) : '{empty}') + '): ' + error)
        })
    }

    render() {
        return (
            <FormRender
                {...this.otherProps}
                {...this.formControl}
                template={this.state.template}
                form={{...this.state.form}}
                messages={this.state.messages}
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
