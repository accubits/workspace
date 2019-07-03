import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NameSubmitComponent } from './name-submit.component';

describe('NameSubmitComponent', () => {
  let component: NameSubmitComponent;
  let fixture: ComponentFixture<NameSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NameSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NameSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
