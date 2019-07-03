import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatGroupChatComponent } from './chat-group-chat.component';

describe('ChatGroupChatComponent', () => {
  let component: ChatGroupChatComponent;
  let fixture: ComponentFixture<ChatGroupChatComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatGroupChatComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatGroupChatComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
