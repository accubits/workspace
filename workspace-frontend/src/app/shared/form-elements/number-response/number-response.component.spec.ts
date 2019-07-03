import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NumberResponseComponent } from './number-response.component';

describe('NumberResponseComponent', () => {
  let component: NumberResponseComponent;
  let fixture: ComponentFixture<NumberResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NumberResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NumberResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
