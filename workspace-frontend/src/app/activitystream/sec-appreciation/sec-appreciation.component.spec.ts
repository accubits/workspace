import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecAppreciationComponent } from './sec-appreciation.component';

describe('SecAppreciationComponent', () => {
  let component: SecAppreciationComponent;
  let fixture: ComponentFixture<SecAppreciationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecAppreciationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecAppreciationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
