import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PhoneSubmitComponent } from './phone-submit.component';

describe('PhoneSubmitComponent', () => {
  let component: PhoneSubmitComponent;
  let fixture: ComponentFixture<PhoneSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PhoneSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PhoneSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
