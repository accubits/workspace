import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-form-creation-wrap',
  templateUrl: './form-creation-wrap.component.html',
  styleUrls: ['./form-creation-wrap.component.scss']
})
export class FormCreationWrapComponent implements OnInit {

  constructor(
    private router: Router,
  ) { }

  ngOnInit() {
  }

}
