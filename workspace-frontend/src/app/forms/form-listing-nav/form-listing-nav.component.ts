import { Component, OnInit } from '@angular/core';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';

@Component({
  selector: 'app-form-listing-nav',
  templateUrl: './form-listing-nav.component.html',
  styleUrls: ['./form-listing-nav.component.scss']
})
export class FormListingNavComponent implements OnInit {
  searchWord: string;
  showSearch:boolean=false;
  constructor(
    public dataService: DataService,
    public formsSandbox: FormsSandbox
  ) { }

  ngOnInit() {
  }

  /*Search Forms */
  searchForms(): void {
    this.formsSandbox.getAllForms();
  }

  pageChanged($event):void{
    this.dataService.getAllForms.page = $event;
    this.formsSandbox.getAllForms();
  }

  ngOnDestroy(){
  //  this.dataService.resetGetAllForms();
  }

}
