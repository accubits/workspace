import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from './../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';

@Component({
  selector: 'app-form-indi-response',
  templateUrl: './form-indi-response.component.html',
  styleUrls: ['./form-indi-response.component.scss']
})

export class FormIndiResponseComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  selectedRsponseindex: number = 0;
  localElementArray: any = [];

  constructor(
    public dataService: DataService,
    private formsSandbox: FormsSandbox,
  ) { }

  ngOnInit() {
    //this.formsSandbox.getAllFormRespose();
  }

  /* Navigate among response */
  navaigateResponse(selNavigation):void{
    selNavigation === 'next'?
    this.dataService.formResponseManagement.reponseDetails['total'] !== this.selectedRsponseindex + 1 ? 
    this.selectedRsponseindex += 1 : this.selectedRsponseindex = this.selectedRsponseindex:
    this.selectedRsponseindex === 0 ? this.selectedRsponseindex = 0 : this.selectedRsponseindex -= 1 ;
    this.dataService.formResponseManagement.selectedAnswerSlug = this.dataService.formResponseManagement.reponseDetails['formResponses'][this.selectedRsponseindex].answersheetSlug;
    this.formsSandbox.getsSingleFormResponseDetails();
  }

}
