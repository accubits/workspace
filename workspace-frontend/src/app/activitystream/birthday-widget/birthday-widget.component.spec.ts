import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BirthdayWidgetComponent } from './birthday-widget.component';

describe('BirthdayWidgetComponent', () => {
  let component: BirthdayWidgetComponent;
  let fixture: ComponentFixture<BirthdayWidgetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BirthdayWidgetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BirthdayWidgetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
