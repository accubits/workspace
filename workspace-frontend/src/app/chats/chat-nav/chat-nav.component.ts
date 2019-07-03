import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-chat-nav',
  templateUrl: './chat-nav.component.html',
  styleUrls: ['./chat-nav.component.scss']
})
export class ChatNavComponent implements OnInit {
  showSearch:boolean =  false;

  constructor() { }

  ngOnInit() {
  }


}
