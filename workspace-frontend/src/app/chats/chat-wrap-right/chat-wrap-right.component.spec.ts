import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatWrapRightComponent } from './chat-wrap-right.component';

describe('ChatWrapRightComponent', () => {
  let component: ChatWrapRightComponent;
  let fixture: ComponentFixture<ChatWrapRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatWrapRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatWrapRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
