import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatNewMessageComponent } from './chat-new-message.component';

describe('ChatNewMessageComponent', () => {
  let component: ChatNewMessageComponent;
  let fixture: ComponentFixture<ChatNewMessageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatNewMessageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatNewMessageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
