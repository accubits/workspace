import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { DataService } from '../../services/data.service';
import { FormsUtilityService } from '../../services/forms-utility.service';

@Component({
  selector: 'app-page-break',
  templateUrl: './page-break.component.html',
  styleUrls: ['./page-break.component.scss']
})
export class PageBreakComponent implements OnInit {
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex: string;
  idx: number;
  currentElement: any = { pagination: { currentPage: 0 } }
  currentPageNumber: number

  /* Data model for number element */
  pageElement = {
    componentId: null,
    action: "create",
    type: "page",
    page: {
      title: "page",
      description: "Next Page"
    }
  }

  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService,
  ) { }

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      if (Object.getOwnPropertyNames(this.currentElement['element']).length === 0) {
        this.currentElement['element'] = this.pageElement;
      } else {
        this.pageElement = this.currentElement['element'];
      }

      let totalPages = this.dataService.formElementArray.filter(
        element => element.name === 'page');

      for (let i = 0; i < totalPages.length; i++) {
        totalPages[i].pagination.currentPage = i + 1;

      }
      this.dataService.formPages.total = totalPages.length;
      console.log('pagess',  this.dataService.formPages.total)
      let idx = totalPages.indexOf(this.currentElement)
      this.currentPageNumber = idx + 1;

     
    }, 100);
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
    /* Page No: [Start]*/
    let totalPages = this.dataService.formElementArray.filter(
      element => element.name === 'page');
    this.dataService.formPages.total = totalPages.length;
        /* Page No: [End]*/


    this.dataService.deletePopup[this.idx] = false;
  }
  /* Deleting the selected form element */

  /* Duplicating the selected form element */
  dulpicateElement() {
    this.formsUtilityService.duplicatingSelectedFormElement(this.currentElementIndex)
  }

  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
  }

}
