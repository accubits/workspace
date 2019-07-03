import { Component, OnInit, Input, Output, EventEmitter, ViewChild, ElementRef } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';
import { Configs } from '../../../config';

@Component({
  selector: 'app-likert',
  templateUrl: './likert.component.html',
  styleUrls: ['./likert.component.scss']
})
export class LikertComponent implements OnInit {
  @ViewChild("focus") trgFocusEl: ElementRef;
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  idx: number;
  currentElement: {};

  /* Data model for likert element */
  likertElement = {
    componentId: null,
    action: 'create',
    type: 'likert',
    likert: {
      label: '',
      statements: [
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        },
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        },
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        }
      ],
      columns: [
        {
          colId: null,
          action: 'create',
          column: ''
        },
        {
          colId: null,
          action: 'create',
          column: ''
        },
        {
          colId: null,
          action: 'create',
          column: ''
        },
      ],
      isRequired: false
    },
    isValidated: false,

  }

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService,
    public formsUtilityService: FormsUtilityService
  ) { }

  ngOnInit() {
    setTimeout(() => {
      // Creating a local instance of form element array
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.likertElement;
      } else {
        this.likertElement = this.currentElement['element'];
        if (this.likertElement.action === 'update') {
          for (var i = 0; i < this.likertElement.likert.statements.length; i++) {
            this.likertElement.likert.statements[i].action = 'update';
          }
          for (var i = 0; i < this.likertElement.likert.columns.length; i++) {
            this.likertElement.likert.columns[i].action = 'update';
          }
        }
      }
    }, 100);
  }

  /* Adding a new column */
  addColumn(event): void {
    let choice = {
      colId: null,
      action: 'create',
      column: ''
    }
    this.likertElement.likert.columns.push(choice);
  }

  /*deleteing column */
  deleteColumn(index): void {
    this.likertElement.likert.columns.splice(index, 1)
  }

  /*deleteing statement */
  deleteStatement(index): void {
    this.likertElement.likert.statements.splice(index, 1)
  }

  /* Adding a new statement */
  addStatement(event): void {
    let choice = {
      stmtId: null,
      action: 'create',
      stmt: ''
    }
    this.likertElement.likert.statements.push(choice);
    event.currentTarget.value = '';
  }

  /* Deleting the selected form element */
  deleteElement() {
    this.idx = 1;
    this.dataService.deletePopup[this.idx] = true;
    this.dataService.deleteCurrentElementIndex = this.currentElementIndex;
  }
  closePopup(): void {
    this.dataService.deletePopup[this.idx] = false;
  }
  deleteConform() {
    this.formsUtilityService.deleteSelectedFormElement(this.dataService.deleteCurrentElementIndex);
    this.dataService.deletePopup[this.idx] = false;
  }
  /* Deleting the selected form element */

  // /* Duplicating the selected form element */
  // dulpicateElement() {
  //   this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  // }

  dulpicateElement() {
      this.formsUtilityService.duplicatingLkertElement(this.currentElementIndex)
    }

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
    setTimeout(() => {
      this.trgFocusEl.nativeElement.focus();
    }, 100);
  }
  validateElement(): void {
    if (this.likertElement.likert.label) {
      // let invalidStmt = this.likertElement.likert.statements.filter(
      //   stmt => stmt.stmt === '')[0];

      // let invalidColumn = this.likertElement.likert.columns.filter(
      //   col => col.column === '')[0];

      // if (invalidStmt || invalidColumn) {
      //   this.likertElement.isValidated = false;
      // } else {
      this.likertElement.isValidated = true;
      // }
    } else {
      this.likertElement.isValidated = false;
    }
    // }
  }
}
