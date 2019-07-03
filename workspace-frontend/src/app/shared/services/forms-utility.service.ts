import { element } from 'protractor';
import { Injectable, EventEmitter } from '@angular/core';
import { DataService } from './data.service';
import { UtilityService } from './utility.service';

@Injectable()
export class FormsUtilityService {
  updateDraft: EventEmitter<boolean> = new EventEmitter();
  invalidElement: EventEmitter<string> = new EventEmitter();
  constructor(
    public dataService: DataService,
    private utilityService: UtilityService
  ) { }

  /* Checking all tasks[Start] */
  checkAllForms($event): void {
    for (let i = 0; i < this.dataService.getAllForms.formListsDeatils.forms.length; i++) {
      this.dataService.getAllForms.formListsDeatils.forms[i].selected = this.dataService.formsSelectionManagement.selectAll;
      this.manageFormSelection(this.dataService.getAllForms.formListsDeatils.forms[i].selected, i)
    }
  }

  /* Checking all tasks[Start] */

  /* Managing form selction[Start] */
  manageFormSelection(isSelcted: boolean, index: number): void {
    // Checking the task is selected
    if (isSelcted) {
      this.dataService.formsSelectionManagement.selectedFormSlugs.push(this.dataService.getAllForms.formListsDeatils.forms[index].formSlug);  // Inserting in to slected task list
    } else {
      let idx = this.dataService.formsSelectionManagement.selectedFormSlugs.indexOf(this.dataService.getAllForms.formListsDeatils.forms[index].formSlug);
      this.dataService.formsSelectionManagement.selectedFormSlugs.splice(idx, 1); // Deleting fron slected task list
    }
    this.dataService.formsSelectionManagement.selectedFormSlugs.length > 0 ? this.dataService.footerOption.show = true : this.dataService.footerOption.show = false;
  }
  /* Managing form selction[End] */

  /* Deleting Selected element form Form[Start] */
  deleteSelectedFormElement(elementIndex): void {
    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    if (index != -1) this.dataService.formElementArray.splice(index, 1)
  }
  /* Deleting Selected element form Form[End] */

  /* Duplicating Selected element form Form[Start] */
  duplicatingSelectedFormElement(elementIndex): void {
    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    selectedElement.element.action = 'create';
    selectedElement.element.componentId = null;
    console.log('selectedElement', selectedElement);
    if (index != -1) {
      let selectedElementCopy = JSON.parse(JSON.stringify(selectedElement));
      selectedElementCopy.index = this.utilityService.generaterandomID();
      
      index + 1 === this.dataService.formElementArray.length ? this.dataService.formElementArray.push(selectedElementCopy) : this.dataService.formElementArray.splice(index + 1, 0, selectedElementCopy);
    }
  }
  /* Duplicating Selected element form Form[End] */

  /* Duplicating Selected element form Form[Start] */
  duplicatingMultipleChoiceElement(elementIndex): void {

    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    selectedElement.element.action = 'create';
    selectedElement.element.componentId = null;
    for(let i = 0; i< selectedElement.element.multipleChoice.choices.length; i++){
      selectedElement.element.multipleChoice.choices[i].action = 'create';
      selectedElement.element.multipleChoice.choices[i].optId = null;
    }
    if (index != -1) {
      let selectedElementCopy = JSON.parse(JSON.stringify(selectedElement));
      selectedElementCopy.index = this.utilityService.generaterandomID();
      
      index + 1 === this.dataService.formElementArray.length ? this.dataService.formElementArray.push(selectedElementCopy) : this.dataService.formElementArray.splice(index + 1, 0, selectedElementCopy);
    }
  }
  /* Duplicating Selected element form Form[End] */

  /* Duplicating Selected element form Form[Start] */
  duplicatingDropDownElement(elementIndex): void {

    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    selectedElement.element.action = 'create';
    selectedElement.element.componentId = null;
    for(let i = 0; i< selectedElement.element.dropdown.choices.length; i++){
      selectedElement.element.dropdown.choices[i].action = 'create';
      selectedElement.element.dropdown.choices[i].optId = null;
    }
    if (index != -1) {
      let selectedElementCopy = JSON.parse(JSON.stringify(selectedElement));
      selectedElementCopy.index = this.utilityService.generaterandomID();
      
      index + 1 === this.dataService.formElementArray.length ? this.dataService.formElementArray.push(selectedElementCopy) : this.dataService.formElementArray.splice(index + 1, 0, selectedElementCopy);
    }
  }
  /* Duplicating Selected element form Form[End] */

  /* Duplicating Selected element form Form[Start] */
  duplicatingCheckboxElement(elementIndex): void {

    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    selectedElement.element.action = 'create';
    selectedElement.element.componentId = null;
    for(let i = 0; i< selectedElement.element.checkboxes.choices.length; i++){
      selectedElement.element.checkboxes.choices[i].action = 'create';
      selectedElement.element.checkboxes.choices[i].optId = null;
    }
    if (index != -1) {
      let selectedElementCopy = JSON.parse(JSON.stringify(selectedElement));
      selectedElementCopy.index = this.utilityService.generaterandomID();
      
      index + 1 === this.dataService.formElementArray.length ? this.dataService.formElementArray.push(selectedElementCopy) : this.dataService.formElementArray.splice(index + 1, 0, selectedElementCopy);
    }
  }
  /* Duplicating Selected element form Form[End] */

   /* Duplicating Selected element form Form[Start] */
   duplicatingLkertElement(elementIndex): void {

    let selectedElement = this.dataService.formElementArray.filter(
      element => element.index === elementIndex)[0];
    let index = this.dataService.formElementArray.indexOf(selectedElement);
    selectedElement.element.action = 'create';
    selectedElement.element.componentId = null;
    for(let i = 0; i< selectedElement.element.likert.columns.length; i++){
      selectedElement.element.likert.columns[i].action = 'create';
      selectedElement.element.likert.columns[i].colId = null;
     }
     for(let i = 0; i< selectedElement.element.likert.statements.length; i++){
      selectedElement.element.likert.statements[i].action = 'create';
      
      selectedElement.element.likert.statements[i].stmtId = null;
     }
    if (index != -1) {
      let selectedElementCopy = JSON.parse(JSON.stringify(selectedElement));
      selectedElementCopy.index = this.utilityService.generaterandomID();
      
      index + 1 === this.dataService.formElementArray.length ? this.dataService.formElementArray.push(selectedElementCopy) : this.dataService.formElementArray.splice(index + 1, 0, selectedElementCopy);
    }
  }
  /* Duplicating Selected element form Form[End] */

  formDraftedConfirmation() {
    this.updateDraft.emit(true);
  }

  focusInvalidElement(elementIndex) {
    this.invalidElement.next(elementIndex);
  }
}
