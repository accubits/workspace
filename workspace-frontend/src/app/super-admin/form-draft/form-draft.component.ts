import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-form-draft',
  templateUrl: './form-draft.component.html',
  styleUrls: ['./form-draft.component.scss']
})
export class FormDraftComponent implements OnInit {
  showOption : boolean = false;

  constructor() { }

  ngOnInit() {
  }

  morepop(event){
    this.showOption =! this.showOption;
    event.stopPropagation();
  }

  check(event){
    event.stopPropagation();
  }

  closeMore(event){
    this.showOption = false;
    event.stopPropagation();
  }

}
