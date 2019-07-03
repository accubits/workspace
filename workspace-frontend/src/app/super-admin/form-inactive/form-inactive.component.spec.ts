import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormInactiveComponent } from './form-inactive.component';

describe('FormInactiveComponent', () => {
  let component: FormInactiveComponent;
  let fixture: ComponentFixture<FormInactiveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormInactiveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormInactiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
