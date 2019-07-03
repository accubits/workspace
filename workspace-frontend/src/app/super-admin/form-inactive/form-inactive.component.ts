import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-form-inactive',
  templateUrl: './form-inactive.component.html',
  styleUrls: ['./form-inactive.component.scss']
})
export class FormInactiveComponent implements OnInit {
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
