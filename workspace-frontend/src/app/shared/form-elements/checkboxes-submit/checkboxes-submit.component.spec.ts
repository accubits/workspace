import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CheckboxesSubmitComponent } from './checkboxes-submit.component';

describe('CheckboxesSubmitComponent', () => {
  let component: CheckboxesSubmitComponent;
  let fixture: ComponentFixture<CheckboxesSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CheckboxesSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CheckboxesSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
